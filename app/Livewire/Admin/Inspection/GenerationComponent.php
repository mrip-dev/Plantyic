<?php

namespace App\Livewire\Admin\Inspection;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\FuelType;
use App\Models\InspectionEnquiry;
use App\Models\Transmission;
use App\Models\User;
use App\Models\VehicleInspectionReport;
use App\Models\VehicleModel;
use App\Models\Vehicle;
use App\Models\VehicleDocument;
use App\Notifications\VehicleInspectionConfirmation;
use App\Notifications\VehicleInspectionReportConfirmation;
use Barryvdh\DomPDF\Facade\Pdf;
use Knp\Snappy\Image as SnappyImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class GenerationComponent extends Component
{
    use WithPagination;
    public $showForm = false;
    public $isEditing = false;
    public $currentStep = 1;
    public $search = null;
    public $report_id = null;
    public $years=[];
    public $inspectionId;

    public bool $showShareModal = false;
    public ?int $reportToShareId = null;
    public ?string $shareExpiryDateTime = null;
    public ?string $generatedShareLink = null;

    public $showDetails = false;
    public ?VehicleInspectionReport $reportInView = null;
    protected string $paginationTheme = 'bootstrap';

    public array $reportData = [];

    public $brands = [], $models = [], $bodyTypes = [], $fuelTypes = [], $transmissions = [];

    protected $listeners = ['showCreateForm', 'showEditForm'];
    public ?string $modalForProperty = null;
    public ?int $linkedVehicleId = null;
    public ?Vehicle $linkedVehicle = null;
    public ?int $linkedEnquiryId = null;

    public function openModal(string $property)
    {
        $this->modalForProperty = $property;
    }
    public function closeModal()
    {
        $this->modalForProperty = null;
    }
    protected function rules()
    {
        return [
            'reportData.make' => 'required',
            'reportData.model' => 'required',
            'reportData.year' => 'required|integer',

        ];
    }
    protected function messages()
    {
        return [
            'reportData.make' => 'Vehicle Make is required',
            'reportData.model' => 'Vehicle Model is required',
            'reportData.year' => 'Vehicle year cannot be null.',
        ];
    }
    private function initializeReportData()
    {
        $this->reportData = (new VehicleInspectionReport())->getAttributes();
        $arrayFields = ['paintCondition', 'frontLeftTire', 'rearRightTire', 'seatsCondition', 'brakeDiscs', 'shockAbsorberOperation'];
        foreach ($arrayFields as $field) {
            $this->reportData[$field] = [];
        }
    }
    public function updatedReportDataMake($value)
    {
        $this->reportData['model'] = null;
        if ($value) {
            $this->models = VehicleModel::where('brand_id', $value)->get();
        } else {
            $this->models = [];
        }
    }

    public function mount($vehicleId = null, $enquiryId = null)
    {

        $this->brands =  Brand::orderBy('name')->whereHas('models')->get();
        $this->years = getYears();
        $this->bodyTypes = BodyType::all();
        $this->fuelTypes = FuelType::all();
        $this->transmissions = Transmission::all();

        if ($vehicleId) {
            $this->linkedVehicleId = $vehicleId;
        }
        if ($enquiryId) {
            $this->linkedEnquiryId = $enquiryId;
        }
    }
    public function loadModels($id)
    {
        if ($id) {
            $this->models = VehicleModel::where('brand_id', $id)->get();
        } else {
            $this->models = [];
        }
    }
    private function loadDataFromVehicle(int $vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);

        if ($vehicle) {
            $this->linkedVehicle = $vehicle;
            $this->reportData['vehicle_id'] = $this->linkedVehicleId;
            $this->reportData['make']           = $vehicle->brand->id;
            $this->loadModels($vehicle->brand->id);
            $this->reportData['model']          = $vehicle->vehicleModel->id;
            $this->reportData['year']           = $vehicle->year;
            $this->reportData['vin']            = $vehicle->vin;
            $this->reportData['engine_cc']      = $vehicle->engine_cc;
            $this->reportData['horsepower']     = $vehicle->horsepower;
            $this->reportData['noOfCylinders']  = $vehicle->no_of_cylinders;
            $this->reportData['transmission']   = $vehicle->transmission->name;
            $this->reportData['color']          = $vehicle->color;
            $this->reportData['specs']          = $vehicle->specs;
            $this->reportData['odometer']          = $vehicle->mileage;
            $this->reportData['noOfCylinders']          = $vehicle->noOfCylinders;
            $this->reportData['body_type']      = $vehicle->bodyType->name;
        }
    }
    private function loadDataFromEnquiry(int $enquiryId)
    {
        $enquiry = InspectionEnquiry::find($enquiryId);
        if ($enquiry) {
            $this->reportData['make']           = $enquiry->make;
            $this->loadModels($enquiry->make);
            $this->reportData['model']          = $enquiry->model;
            $this->reportData['year']           = $enquiry->year;
        }
    }
    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        if ($this->currentStep < 3) {
            $this->currentStep++;
        } elseif ($this->currentStep >= 3) {
            $this->saveReport();
        }
    }
    private function validateStep(int $step)
    {
        $rulesForStep = [];
        if ($step === 1) {
            $rulesForStep = [
                'reportData.make' => 'required',
                'reportData.model' => 'required',
                'reportData.year' => 'required|integer',

            ];
        } elseif ($step === 2) {
            $rulesForStep = [

                'reportData.make' => 'required',
                'reportData.model' => 'required',
                'reportData.year' => 'required|integer',
            ];
        } elseif ($step === 3) {
            $rulesForStep = [
                'reportData.make' => 'required',
                'reportData.model' => 'required',
                'reportData.year' => 'required|integer',
            ];
        } elseif ($step === 4) {
            $rulesForStep = [
                'reportData.make' => 'required',
                'reportData.model' => 'required',
                'reportData.year' => 'required|integer',
            ];
        }

        $this->validate($rulesForStep);
    }
    public function prevStep()
    {
        if ($this->currentStep > 1) $this->currentStep--;
    }
    public function showCreateForm()
    {
        $this->initializeReportData();
        $this->isEditing = false;
        $this->currentStep = 1;
        $this->showForm = true;
        $this->showDetails = false;
        $this->inspectionId = null;
        if ($this->linkedEnquiryId) {
            $this->loadDataFromEnquiry($this->linkedEnquiryId);
        } elseif ($this->linkedVehicleId) {
            $this->loadDataFromVehicle($this->linkedVehicleId);
        }
    }

    public function showEditForm($reportId)
    {
        $this->initializeReportData();
        $this->isEditing = true;
        $report = VehicleInspectionReport::findOrFail($reportId);
        $this->report_id = $reportId;
        $this->inspectionId = $reportId;
        $this->reportData = $report->toArray();
        $this->currentStep = 1;
        $this->showForm = true;
        $this->showDetails = false;
        $this->loadModels($this->reportData['make']);
    }
    public function showReportDetails($reportId)
    {
        $this->reportInView = VehicleInspectionReport::findOrFail($reportId);
        $this->showDetails = true;
        $this->showForm = false;
    }
    public function saveReport()
    {
        $this->validate();
        $this->reportData['vehicle_id'] = $this->linkedVehicleId;
        $this->reportData['inspection_enquiry_id'] = $this->linkedEnquiryId;
        $inspection = VehicleInspectionReport::updateOrCreate(['id' => $this->inspectionId], $this->reportData);
        $this->inspectionId = $inspection->id;
        if ($this->currentStep == 3) {
            $this->currentStep++;
        } else {
            $this->generatePdf($this->inspectionId);
            session()->flash('success', $this->isEditing ? 'Report updated successfully.' : 'Report created successfully.');
            $this->cancel();
        }
    }
    public function saveReportDraft()
    {
        $this->validateStep($this->currentStep);
        $this->reportData['vehicle_id'] = $this->linkedVehicleId;
        $this->reportData['inspection_enquiry_id'] = $this->linkedEnquiryId;
        $inspection = VehicleInspectionReport::updateOrCreate(['id' => $this->inspectionId], $this->reportData);
        $this->inspectionId = $inspection->id;
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->showDetails = false;
        $this->reportInView = null;
        $this->reset(['reportData']);
    }

    public function generatePdf($reportId)
    {
        $reportInView = VehicleInspectionReport::findOrFail($reportId);
        if ($reportInView->damage_file_path && Storage::disk('public')->exists($reportInView->damage_file_path)) {
            Storage::disk('public')->delete($reportInView->damage_file_path);
            VehicleDocument::where('file_path', $reportInView->damage_file_path)->delete();
        }

        if ($reportInView->file_path && Storage::disk('public')->exists($reportInView->file_path)) {
            Storage::disk('public')->delete($reportInView->file_path);
            VehicleDocument::where('file_path', $reportInView->file_path)->delete();
        }
        foreach (Storage::disk('public')->files('damage-assessments') as $path) {
            if (Str::startsWith(basename($path), 'damage-report-' . $reportInView->id . '-')) {
                Storage::disk('public')->delete($path);
                VehicleDocument::where('file_path', $path)->delete();
            }
        }
        foreach (Storage::disk('public')->files('inspection_pdf') as $path) {
            if (Str::startsWith(basename($path), 'inspection_' . $reportInView->id . '_')) {
                Storage::disk('public')->delete($path);
                VehicleDocument::where('file_path', $path)->delete();
            }
        }
        $is_image = true;
        if ($is_image) {
            $html = view('pdf.inspection.damage-assessment-image', compact('reportInView'))->render();
            $snappyImage = app('snappy.image');
            $pngOptions = [
                'width' => 1200,
                'quality' => 90,
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
                'disable-smart-width' => true,
                'javascript-delay' => 300,
                'no-stop-slow-scripts' => true,
                'load-error-handling' => 'ignore',
            ];
            $pngBinary = $snappyImage->getOutputFromHtml($html, $pngOptions);
            $pngDir = 'damage-assessments';
            if (!Storage::disk('public')->exists($pngDir)) {
                Storage::disk('public')->makeDirectory($pngDir);
            }
            $pngFilename = 'damage-report-' . $reportInView->id . '-' . now()->format('Ymd_His') . '.png';
            $pngPath = $pngDir . '/' . $pngFilename;

            Storage::disk('public')->put($pngPath, $pngBinary);
            if ($reportInView->vehicle_id) {
                $imageDoc = new VehicleDocument();
                $imageDoc->vehicle_id  =  $reportInView->vehicle_id;
                $imageDoc->file_path = $pngPath;
                $imageDoc->type = 'InspectionReportImage';
                $imageDoc->save();
            }
            $reportInView->damage_file_path = $pngPath;
            $reportInView->save();
        }
        $damageAssessmentLink = URL::temporarySignedRoute(
            'inspection.report.damage-assessment.view', // The name of the route we created
            now()->addDays(30), // Set the expiration time for the link
            ['report' => $reportInView->id] // Pass the report ID to the route
        );

        $directory = 'inspection_pdf';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $pdf = Pdf::loadView('pdf.inspection.report-pdf-template', [
            'reportInView' => $reportInView,
            'damageAssessmentLink' => $damageAssessmentLink // <-- Pass the link here
        ])
            ->setPaper('a4', 'portrait');

        $filename = 'inspection_' . $reportInView->id  . '_' . now()->format('Ymd_His') . '.pdf';
        $filepath = $directory . '/' . $filename;

        Storage::disk('public')->put($filepath, $pdf->output());

        if ($reportInView->vehicle_id) {
            $vehicleDoc = new VehicleDocument();
            $vehicleDoc->vehicle_id  =  $reportInView->vehicle_id;
            $vehicleDoc->file_path = $filepath;
            $vehicleDoc->type = 'InspectionReport';
            $vehicleDoc->save();
        }

        $reportInView->file_path = $filepath;
        $reportInView->save();
        $this->dispatch('success-notification', message: 'Report Generated Successfully');
        $this->js('setTimeout(() => window.location.reload(), 1200)');

        return; // important: stop here, don’t also return a redirect
    }

    #[On('deleteReport')]
    public function deleteReport($id)
    {
        VehicleInspectionReport::findOrFail($id)->delete();

        $this->dispatch('success-notification', message: 'Item Deleted Successfully');
    }

    public function setSingleSelection(string $property, $value)
    {
        $currentValue = $this->reportData[$property] ?? null;
        $this->reportData[$property] = ($currentValue == $value) ? null : $value;
    }

    public function toggleArrayValue(string $property, string $value)
    {
        $array = $this->reportData[$property] ?? [];
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        } else {
            $array[] = $value;
        }
        $this->reportData[$property] = array_values($array);
    }

    public function openShareModal(int $reportId)
    {
        $report = VehicleInspectionReport::findOrFail($reportId);
        $this->reportToShareId = $report->id;


        $this->generatedShareLink = null;
        $this->shareExpiryDateTime = now()->addDay()->format('Y-m-d\TH:i');


        $this->showShareModal = true;
    }

    public function closeShareModal()
    {
        $this->showShareModal = false;
        $this->reset(['reportToShareId', 'shareExpiryDateTime', 'generatedShareLink']);
    }
    public function generateShareableLink()
    {
        $this->validate(['shareExpiryDateTime' => 'required|date']);
        try {
            $expiry = Carbon::parse($this->shareExpiryDateTime);

            if ($expiry->isPast()) {
                $this->addError('shareExpiryDateTime', 'The expiry date must be in the future.');
                return;
            }
            $this->generatedShareLink = URL::temporarySignedRoute(
                'inspection.report.download.signed',
                $expiry,
                ['report' => $this->reportToShareId]
            );
            $report = VehicleInspectionReport::find($this->reportToShareId);
            $report->update([
                'shared_link' => $this->generatedShareLink,
                'shared_link_expires_at' => $expiry,
            ]);
            if ($report &&  $report->inspection_enquiry_id) {
                $enquiry = InspectionEnquiry::find($report->inspection_enquiry_id);
                if ($enquiry) {
                    $userEmail = $enquiry->email;
                    $user = User::where('email', $userEmail)->first();
                    if ($user) {
                        Notification::send($user, new VehicleInspectionConfirmation($enquiry));
                    }
                }
            }
            $this->dispatch('success-notification', message: 'Sharable link generated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('error-notification', message: 'Failed to generate link. ' . $e->getMessage());
        }
    }
    public function render()
    {

        $query = VehicleInspectionReport::query();
        if ($this->linkedEnquiryId) {
            $query->where('inspection_enquiry_id', $this->linkedEnquiryId);
        } elseif ($this->linkedVehicleId) {
            $query->where('vehicle_id', $this->linkedVehicleId);
        }
        if ($this->search) {
            $query->where(function ($searchQuery) {
                $searchQuery->where('vin', 'like', '%' . $this->search . '%')
                    ->orWhere('make', 'like', '%' . $this->search . '%');
            });
        }
        $reports = $query->latest()->paginate(10);
        $this->dispatch('re-init-select-2-component');
        return view('livewire.admin.inspection.generation-component', [
            'reports' => $reports
        ]);
    }
}
