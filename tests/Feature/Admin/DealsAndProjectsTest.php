<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\Stage;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DealsAndProjectsTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected Pipeline $pipeline;
    protected Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->pipeline = Pipeline::create(['name' => 'Sales Pipeline']);
        $this->stage = Stage::create([
            'name' => 'Lead',
            'pipeline_id' => $this->pipeline->id,
            'order' => 1
        ]);

        // Ensure upload directory exists for tests
        File::ensureDirectoryExists(public_path('uploads/agreements'));
    }

    protected function tearDown(): void
    {
        // Clean up test-uploaded files from public/uploads/agreements
        $files = File::files(public_path('uploads/agreements'));
        foreach ($files as $file) {
            if (str_contains($file->getFilename(), 'perjanjian_deal') ||
                str_contains($file->getFilename(), 'kontrak_project')) {
                File::delete($file->getPathname());
            }
        }
        parent::tearDown();
    }

    public function test_admin_can_view_deals_index_page()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.deals.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.deals.index');
        $response->assertSeeText('Enterprise Sales Pipeline & CRM');
    }

    public function test_admin_can_create_deal_with_agreement_document()
    {
        $file = UploadedFile::fake()->create('perjanjian_deal.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.deals.store'), [
            'name' => 'Deal Custom ERP Bagema',
            'price' => 15000000,
            'phone' => '08123456789',
            'pipeline_id' => $this->pipeline->id,
            'stage_id' => $this->stage->id,
            'agreement_document' => $file,
        ]);

        $response->assertRedirect(route('admin.deals.index'));

        $this->assertDatabaseHas('deals', [
            'name' => 'Deal Custom ERP Bagema',
            'price' => 15000000,
        ]);

        $deal = Deal::where('name', 'Deal Custom ERP Bagema')->first();
        $this->assertNotNull($deal->agreement_document);

        // File is stored in public/uploads/agreements/ (not storage disk)
        $filePath = public_path(ltrim($deal->agreement_document, '/'));
        $this->assertTrue(
            File::exists($filePath),
            "File not found at [{$filePath}]"
        );
    }

    public function test_admin_can_view_projects_index_page()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.projects.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.projects.index');
        $response->assertSee('Deployment Projects');
    }

    public function test_admin_can_create_project_with_agreement_document()
    {
        $file = UploadedFile::fake()->create('kontrak_project.pdf', 800, 'application/pdf');
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.projects.store'), [
            'project_name' => 'Project Integrasi Inventory',
            'customer_id' => $customer->id,
            'budget' => 25000000,
            'status' => 'in_progress',
            'agreement_document' => $file,
        ]);

        $response->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', [
            'project_name' => 'Project Integrasi Inventory',
            'budget' => 25000000,
            'status' => 'in_progress',
        ]);

        $project = Project::where('project_name', 'Project Integrasi Inventory')->first();
        $this->assertNotNull($project->agreement_document);

        // File is stored in public/uploads/agreements/ (not storage disk)
        $filePath = public_path(ltrim($project->agreement_document, '/'));
        $this->assertTrue(
            File::exists($filePath),
            "File not found at [{$filePath}]"
        );
    }
}
