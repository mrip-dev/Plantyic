<?php

namespace App\Livewire\Admin\Inspection;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\FuelType;
use App\Models\InspectionEnquiry;
use App\Models\Transmission;
use App\Models\VehicleInspectionReport;
use App\Models\VehicleModel;
use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Knp\Snappy\Image as SnappyImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class GenerationComponent extends Component
{
    use WithPagination;
    public $showForm = false;
    public $isEditing = false;
    public $currentStep = 1;
    public $search = null;
    public $report_id = null;
    public $inspectionId;

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


        $this->brands =  Brand::orderBy('name')->where('is_active', 1)->get();
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
    private function loadDataFromVehicle(int $vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);

        if ($vehicle) {
            $this->linkedVehicle = $vehicle;
            $this->reportData['vehicle_id'] = $this->linkedVehicleId;
            $this->reportData['make']           = $vehicle->brand->name;
            $this->reportData['model']          = $vehicle->vehicleModel->name;
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

        // 1) Generate PDF (DomPDF - unchanged)
        $directory = 'inspection_pdf';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $pdf = Pdf::loadView('pdf.inspection.report-pdf-template', ['reportInView' => $reportInView])
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

        // 2) Generate PNG (wkhtmltoimage via Snappy)
        // Make sure the Blade below is fully server-rendered (no client-only JS).
        $html = view('pdf.inspection.damage-assessment-image', compact('reportInView'))->render();


        /** @var \Knp\Snappy\Image $snappyImage */
        $snappyImage = app('snappy.image');

        $pngOptions = [
            // Dimensions and quality
            'width' => 1200,
            'quality' => 90,
            'encoding' => 'UTF-8',

            // If you reference local files (file://), allow access
            'enable-local-file-access' => true,

            // Layout/JS timing
            'disable-smart-width' => true,  // keeps the width you set instead of auto-resizing
            'javascript-delay' => 300,      // small delay so fonts/images can load
            'no-stop-slow-scripts' => true,

            // Error handling (supported in newer wkhtmltoimage)
            'load-error-handling' => 'ignore', // use 'abort' to fail loudly when debugging
        ];

        $pngBinary = $snappyImage->getOutputFromHtml($html, $pngOptions);
        $pngDir = 'damage-assessments';
        if (!Storage::disk('public')->exists($pngDir)) {
            Storage::disk('public')->makeDirectory($pngDir);
        }

        $pngFilename = 'damage-report-' . $reportInView->id . '-' . now()->format('Ymd_His') . '.png';
        $pngPath = $pngDir . '/' . $pngFilename;

        Storage::disk('public')->put($pngPath, $pngBinary);

        // Optional: store the PNG as a VehicleDocument record (separate type)
        if ($reportInView->vehicle_id) {
            $imageDoc = new VehicleDocument();
            $imageDoc->vehicle_id  =  $reportInView->vehicle_id;
            $imageDoc->file_path = $pngPath;
            $imageDoc->type = 'InspectionReportImage';
            $imageDoc->save();
        }

        $this->dispatch('success-notification', message: 'Report Generated Successfully');
        $this->reset();
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
