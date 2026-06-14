<?php

namespace App\Services\Cms;

use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\LandingSection;
use App\Models\CompanyInfo;
use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Collection;

class ContentService
{
    /**
     * Get all active FAQs
     */
    public function getFaqs(?string $category = null): Collection
    {
        $query = Faq::active()->ordered();

        if ($category) {
            $query->category($category);
        }

        return $query->get();
    }

    /**
     * Get FAQ categories
     */
    public function getFaqCategories(): array
    {
        return Faq::active()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Get all active testimonials
     */
    public function getTestimonials(?string $productType = null, bool $featuredOnly = false): Collection
    {
        $query = Testimonial::active()->ordered();

        if ($featuredOnly) {
            $query->featured();
        }

        if ($productType) {
            $query->productType($productType);
        }

        return $query->get();
    }

    /**
     * Get landing section by key
     */
    public function getLandingSection(string $sectionKey): ?array
    {
        $section = LandingSection::active()
            ->where('section_key', $sectionKey)
            ->first();

        return $section?->content;
    }

    /**
     * Get all active landing sections
     */
    public function getAllLandingSections(): Collection
    {
        return LandingSection::active()->ordered()->get();
    }

    /**
     * Get company info by group
     */
    public function getCompanyInfoByGroup(string $group): array
    {
        return CompanyInfo::active()
            ->where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get specific company info
     */
    public function getCompanyInfo(string $key, mixed $default = null): mixed
    {
        return CompanyInfo::get($key, $default);
    }

    /**
     * Get email template by key
     */
    public function getEmailTemplate(string $key): ?EmailTemplate
    {
        return EmailTemplate::active()
            ->where('key', $key)
            ->first();
    }

    /**
     * Render email template with data
     */
    public function renderEmailTemplate(string $key, array $data = []): ?array
    {
        $template = $this->getEmailTemplate($key);

        if (!$template) {
            return null;
        }

        return $template->render($data);
    }

    /**
     * Create or update FAQ
     */
    public function createOrUpdateFaq(array $data): Faq
    {
        return Faq::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'question' => $data['question'],
                'answer' => $data['answer'],
                'category' => $data['category'] ?? 'general',
                'order' => $data['order'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'updated_by' => $data['updated_by'] ?? null,
            ]
        );
    }

    /**
     * Create or update Testimonial
     */
    public function createOrUpdateTestimonial(array $data): Testimonial
    {
        return Testimonial::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['name'],
                'position' => $data['position'] ?? null,
                'company' => $data['company'] ?? null,
                'content' => $data['content'],
                'avatar' => $data['avatar'] ?? null,
                'rating' => $data['rating'] ?? 5,
                'product_type' => $data['product_type'] ?? null,
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => $data['is_active'] ?? true,
                'order' => $data['order'] ?? 0,
                'customer_id' => $data['customer_id'] ?? null,
                'updated_by' => $data['updated_by'] ?? null,
            ]
        );
    }

    /**
     * Create or update Landing Section
     */
    public function createOrUpdateLandingSection(array $data): LandingSection
    {
        return LandingSection::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['name'],
                'section_key' => $data['section_key'],
                'content' => $data['content'],
                'is_active' => $data['is_active'] ?? true,
                'order' => $data['order'] ?? 0,
                'updated_by' => $data['updated_by'] ?? null,
            ]
        );
    }

    /**
     * Create or update Company Info
     */
    public function createOrUpdateCompanyInfo(array $data): CompanyInfo
    {
        return CompanyInfo::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'key' => $data['key'],
                'value' => $data['value'],
                'type' => $data['type'] ?? 'text',
                'group' => $data['group'] ?? 'general',
                'is_active' => $data['is_active'] ?? true,
                'updated_by' => $data['updated_by'] ?? null,
            ]
        );
    }

    /**
     * Create or update Email Template
     */
    public function createOrUpdateEmailTemplate(array $data): EmailTemplate
    {
        return EmailTemplate::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['name'],
                'key' => $data['key'],
                'subject' => $data['subject'],
                'body_html' => $data['body_html'],
                'body_text' => $data['body_text'] ?? null,
                'variables' => $data['variables'] ?? null,
                'category' => $data['category'] ?? 'transactional',
                'is_active' => $data['is_active'] ?? true,
                'updated_by' => $data['updated_by'] ?? null,
            ]
        );
    }
}
