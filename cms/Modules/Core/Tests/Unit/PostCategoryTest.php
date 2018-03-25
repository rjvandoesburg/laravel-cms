<?php

namespace Cms\Modules\Core\Tests\Unit;

use Cms\Core\Tests\TestCase;
use Cms\Modules\Core\Models\PostCategory;

class PostCategoryTest extends TestCase
{

    /** @test */
    public function a_post_category_will_generate_a_slug_when_not_present_on_create()
    {
        $category = PostCategory::create([
            'name' => 'Category 1',
            'description' => 'blaat'
        ]);

        $this->assertEquals(str_slug('Category 1'), $category->slug);
    }

    /** @test */
    public function a_post_category_will_generate_a_slug_when_not_present_on_update()
    {
        $category = factory(PostCategory::class)->create([
            'slug' => 'old-slug'
        ]);

        $this->assertEquals('old-slug', $category->slug);

        $category->update([
            'slug' => 'new-slug'
        ]);

        $this->assertEquals(str_slug('new-slug'), $category->slug);
    }

    /** @test */
    public function a_post_category_will_create_a_unique_slug_if_slug_is_already_present()
    {
        $this->markTestSkipped('Not yet implemented');

        factory(PostCategory::class)->create([
            'slug' => 'existing-slug'
        ]);

        $category = factory(PostCategory::class)->create([
            'slug' => 'existing-slug'
        ]);

        $this->assertEquals('existing-slug-1', $category->slug);
    }
}