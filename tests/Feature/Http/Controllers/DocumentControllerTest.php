<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_request_redirects_to_login(): void
    {
        $this->get(route('documents.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_lists_documents_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'title' => 'تعميم الحضور',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index'))
            ->assertOk()
            ->assertSee('تعميم الحضور')
            ->assertSee('أرشيف المستندات');

        $this->assertModelExists($document);
    }

    public function test_index_filters_documents_by_search_term(): void
    {
        $user = User::factory()->create();
        Document::factory()->create([
            'title' => 'تعميم الحضور',
            'recipient_name' => 'سارة أحمد',
        ]);
        Document::factory()->create([
            'title' => 'إشعار غياب',
            'recipient_name' => 'نورة سالم',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index', ['q' => 'سارة']))
            ->assertOk()
            ->assertSee('تعميم الحضور')
            ->assertDontSee('إشعار غياب');
    }

    public function test_index_filters_documents_by_category(): void
    {
        $user = User::factory()->create();
        Document::factory()->forTeachers()->create([
            'title' => 'مستند المعلمات',
        ]);
        Document::factory()->forParents()->create([
            'title' => 'مستند أولياء الأمور',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index', ['category' => 'teachers']))
            ->assertOk()
            ->assertSee('مستند المعلمات')
            ->assertDontSee('مستند أولياء الأمور');
    }

    public function test_index_filters_documents_by_status(): void
    {
        $user = User::factory()->create();
        Document::factory()->pending()->create([
            'title' => 'مستند معلق',
        ]);
        Document::factory()->completed()->create([
            'title' => 'مستند مكتمل',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index', ['status' => 'completed']))
            ->assertOk()
            ->assertSee('مستند مكتمل')
            ->assertDontSee('مستند معلق');
    }

    public function test_index_filters_documents_by_date_range(): void
    {
        $user = User::factory()->create();

        $this->travelTo('2026-01-10 12:00:00');
        Document::factory()->create([
            'title' => 'مستند يناير',
        ]);

        $this->travelTo('2026-03-15 12:00:00');
        Document::factory()->create([
            'title' => 'مستند مارس',
        ]);

        $this->travelTo('2026-09-11 12:00:00');

        $this->actingAs($user)
            ->get(route('documents.index', [
                'from' => '2026-03-01',
                'to' => '2026-03-31',
            ]))
            ->assertOk()
            ->assertSee('مستند مارس')
            ->assertDontSee('مستند يناير');
    }

    public function test_index_rejects_an_unknown_status_filter(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('documents.index'))
            ->get(route('documents.index', ['status' => 'hacked']))
            ->assertRedirect(route('documents.index'))
            ->assertSessionHasErrors('status');
    }

    public function test_index_escapes_dangerous_document_titles(): void
    {
        $user = User::factory()->create();
        Document::factory()->create([
            'title' => "<script>alert('xss')</script>",
        ]);

        $this->actingAs($user)
            ->get(route('documents.index'))
            ->assertOk()
            ->assertSee("<script>alert('xss')</script>")
            ->assertDontSee("<script>alert('xss')</script>", false);
    }
}
