import re
import os

VIEWS_DIR = r"c:\laragon\www\cooca-id\resources\views"

# Mapping of file paths to their new tbody contents
replacements = {
    r"admin\cms\pages\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($pages ?? [] as $page)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $page->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $page->title }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">/{{ $page->slug }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $page->created_at ? $page->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">Edit</a>
                        <form action="{{ route('admin.cms.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No pages found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\emailtemplates\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($templates ?? [] as $template)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $template->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $template->name }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $template->subject }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $template->created_at ? $template->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">Edit</a>
                        <form action="{{ route('admin.email-templates.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No email templates found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\faqs\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($faqs ?? [] as $faq)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $faq->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ Str::limit($faq->question, 50) }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ Str::limit($faq->answer, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $faq->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $faq->sort_order ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">Edit</a>
                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No FAQs found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\erprequests\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($requests ?? [] as $erp)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $erp->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $erp->customer->name ?? 'Unknown Customer' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $erp->company_name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ ucfirst($erp->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $erp->created_at ? $erp->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.erp-requests.show', $erp->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No ERP requests found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\emailcampaigns\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($campaigns ?? [] as $campaign)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $campaign->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $campaign->name }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ Str::limit($campaign->subject, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($campaign->status ?? 'draft') == 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300' }}">
                            {{ ucfirst($campaign->status ?? 'draft') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $campaign->created_at ? $campaign->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.email-campaigns.show', $campaign->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No email campaigns found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\testimonials\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($testimonials ?? [] as $testimonial)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $testimonial->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $testimonial->name }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $testimonial->role ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->is_featured ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300' }}">
                            {{ $testimonial->is_featured ? 'Featured' : 'Standard' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $testimonial->created_at ? $testimonial->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">Edit</a>
                        <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No testimonials found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"admin\settlements\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($settlements ?? [] as $settlement)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $settlement->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $settlement->affiliator->name ?? 'Unknown Affiliator' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">Rp {{ number_format($settlement->amount ?? 0, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($settlement->status ?? 'pending') {
                                'approved', 'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($settlement->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $settlement->created_at ? $settlement->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.settlements.show', $settlement->id) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No settlement requests found.</td></tr>
                @endforelse
            </tbody>""",
            
    r"affiliator\reviews\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($reviews ?? [] as $review)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $review->title ?? 'No Title' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $review->customer->name ?? 'Unknown Customer' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($review->status ?? 'pending') {
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($review->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <span class="text-surface-400">View Only</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No reviews found from your referred customers.</td></tr>
                @endforelse
            </tbody>""",
            
    r"affiliator\reviews\my_reviews.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($reviews ?? [] as $review)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $review->title ?? 'No Title' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $review->product->name ?? 'Unknown Product' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($review->status ?? 'pending') {
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($review->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <span class="text-surface-400">Read Only</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">You haven't written any reviews yet.</td></tr>
                @endforelse
            </tbody>""",
            
    r"customer\reviews\index.blade.php": r"""<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($reviews ?? [] as $review)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ $review->title ?? 'No Title' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ $review->product->name ?? 'Unknown Product' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($review->status ?? 'pending') {
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($review->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('customer.reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">You haven't written any reviews yet.</td></tr>
                @endforelse
            </tbody>""",
}

def replace_in_file(filepath, new_tbody):
    full_path = os.path.join(VIEWS_DIR, filepath)
    if not os.path.exists(full_path):
        print(f"File not found: {full_path}")
        return False
        
    with open(full_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Replace the exact tbody string
    # We'll use a regex to replace everything between <tbody...> and </tbody>
    pattern = re.compile(r'<tbody[^>]*>.*?</tbody>', re.DOTALL | re.IGNORECASE)
    
    # Also remove "Data populated successfully." which is usually near the bottom of these files
    content = content.replace('<p class="text-sm text-surface-500 dark:text-surface-400 text-center">Data populated successfully.</p>', '')
    
    # Check if "Add New" button needs removal or fixing (for reviews, it shouldn't exist for affiliator view of customers)
    if 'affiliator\\reviews\\index.blade.php' in filepath:
        content = re.sub(r'<a href="#"[^>]*>.*?Add New.*?</a>', '', content, flags=re.DOTALL)
        
    new_content = pattern.sub(new_tbody, content)
    
    if new_content == content:
        print(f"No changes made to {filepath}")
        return False
        
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
        
    print(f"Successfully updated {filepath}")
    return True

for filepath, new_tbody in replacements.items():
    replace_in_file(filepath, new_tbody)
