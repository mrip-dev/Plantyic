<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Workspace;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OnboardingService
{
    protected $workspaceService;
    protected $projectService;

    public function __construct(WorkspaceService $workspaceService, ProjectService $projectService)
    {
        $this->workspaceService = $workspaceService;
        $this->projectService = $projectService;
    }

    /**
     * Complete user onboarding with organization, workspace, and projects
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function completeOnboarding(User $user, array $data): array
    {
        try {
            DB::beginTransaction();

            // Create organization
            $organization = $this->createOrganization($user, $data['organization'] ?? []);

            // Create workspace(s)
            $workspace = $this->createWorkspace($organization, $data['workspace'] ?? []);

            // Create project(s)
            $project = $this->createProject($workspace, $data['project'] ?? []);

            // Save onboarding questions and answers
            $this->saveOnboardingData($organization, $data['onboarding'] ?? []);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Onboarding completed successfully',
                'data' => [
                    'organization' => [
                        'id' => $organization->id,
                        'name' => $organization->name,
                        'slug' => $organization->slug,
                    ],
                    'workspace' => [
                        'id' => $workspace->id,
                        'name' => $workspace->name,
                        'slug' => $workspace->slug,
                    ],
                    'project' => [
                        'id' => $project->id,
                        'name' => $project->name,
                        'slug' => $project->slug,
                    ],
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            // Provide human-readable error messages
            $message = $this->getHumanReadableError($e);

            return [
                'status' => 'error',
                'message' => $message,
            ];
        }
    }

    /**
     * Get human-readable error message from exception
     *
     * @param \Exception $e
     * @return string
     */
    private function getHumanReadableError(\Exception $e): string
    {
        $exceptionMessage = $e->getMessage();

        // Check for duplicate slug errors
        if (strpos($exceptionMessage, 'Duplicate entry') !== false) {
            if (strpos($exceptionMessage, 'workspaces_slug_unique') !== false ||
                strpos($exceptionMessage, 'workspaces') !== false && strpos($exceptionMessage, 'slug') !== false) {
                return 'A workspace with this name already exists in your organization. Please use a different name.';
            }
            if (strpos($exceptionMessage, 'projects_slug_unique') !== false ||
                strpos($exceptionMessage, 'projects') !== false && strpos($exceptionMessage, 'slug') !== false) {
                return 'A project with this name already exists in your workspace. Please use a different name.';
            }
            if (strpos($exceptionMessage, 'organizations_slug_unique') !== false) {
                return 'An organization with this name already exists. Please use a different name.';
            }
            return 'A duplicate entry was found. Please use different names for your organization, workspace, or project.';
        }

        // Check for foreign key constraint errors
        if (strpos($exceptionMessage, 'foreign key') !== false) {
            return 'There was an issue with the organization or workspace relationship. Please try again.';
        }

        // Check for database connection errors
        if (strpos($exceptionMessage, 'SQLSTATE') !== false || strpos($exceptionMessage, 'Connection') !== false) {
            return 'Database connection error. Please try again later.';
        }

        // Default error message (don't expose raw SQL errors to frontend)
        return 'An error occurred while setting up your workspace. Please try again with different names.';
    }

    /**
     * Create organization
     *
     * @param User $user
     * @param array $data
     * @return Organization
     */
    private function createOrganization(User $user, array $data): Organization
    {
        $organizationData = [
            'user_id' => $user->id,
            'name' => $data['name'] ?? 'My Organization',
            'slug' => Organization::generateSlug($data['name'] ?? 'My Organization'),
            'description' => $data['description'] ?? null,
        ];

        return Organization::create($organizationData);
    }

    /**
     * Create workspace
     *
     * @param Organization $organization
     * @param array $data
     * @return Workspace
     */
    private function createWorkspace(Organization $organization, array $data): Workspace
    {
        $workspaceData = [
            'user_id' => $organization->user_id,
            'organization_id' => $organization->id,
            'name' => $data['name'] ?? 'Default Workspace',
            'slug' => Workspace::generateSlug($data['name'] ?? 'Default Workspace', $organization->id),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? null,
            'color' => $data['color'] ?? null,
            'plan' => $data['plan'] ?? 'free',
        ];

        return Workspace::create($workspaceData);
    }

    /**
     * Create project
     *
     * @param Workspace $workspace
     * @param array $data
     * @return Project
     */
    private function createProject(Workspace $workspace, array $data): Project
    {
        $projectData = [
            'workspace_id' => $workspace->id,
            'name' => $data['name'] ?? 'Default Project',
            'slug' => Project::generateSlug($data['name'] ?? 'Default Project', $workspace->id),
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
        ];

        return Project::create($projectData);
    }

    /**
     * Save onboarding questions and answers
     *
     * @param Organization $organization
     * @param array $onboardingData
     * @return void
     */
    private function saveOnboardingData(Organization $organization, array $onboardingData): void
    {
        if (empty($onboardingData)) {
            return;
        }

        $updateData = [];

        if (isset($onboardingData['questions'])) {
            $updateData['onboarding_questions'] = $onboardingData['questions'];
        }

        if (isset($onboardingData['answers'])) {
            $updateData['onboarding_answers'] = $onboardingData['answers'];
        }

        if (!empty($updateData)) {
            $organization->update($updateData);
        }
    }
}
