<?php

namespace Cms\Modules\Core\Tests\Unit;

use Cms\Core\Tests\TestCase;
use Cms\Framework\Foundation\Testing\DatabaseMigrations;
use Cms\Modules\Core\Models\Post;
use Cms\Modules\Core\Models\User;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \Cms\Modules\Core\Models\Post
     */
    protected $post;

    /**
     * This method is called before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->post = factory(Post::class)->create();
    }
    
    /** @test */
    public function a_post_has_an_author()
    {
        $this->assertInstanceOf(User::class, $this->post->author);
    }

    /** @test */
    public function a_post_has_an_creator()
    {
        $this->assertInstanceOf(User::class, $this->post->creator);
    }

    /** @test */
    public function a_post_is_by_default_set_to_draft()
    {
        $this->assertTrue($this->post->isDraft());
    }

    /** @test */
    public function a_post_can_be_soft_deleted()
    {
        $this->post->delete();

        $this->assertTrue($this->post->trashed());
    }

    /** @test */
    public function a_post_can_be_published()
    {
        $this->assertFalse($this->post->published());

        $this->post->publish();

        $this->assertTrue($this->post->fresh()->published());

        // check if the record in the DB is also updated, not just the model
        $this->assertTrue(Post::find($this->post->id)->published());
    }

    /** @test */
    public function posts_can_be_filtered_by_draft_status()
    {
        factory(Post::class)->create(['status' => Post::STATUS_PUBLISHED]);

        $this->assertEquals(1, Post::status(Post::STATUS_DRAFT)->count());
    }

    /** @test */
    public function posts_can_be_filtered_by_publish_status()
    {
        factory(Post::class)->create(['status' => Post::STATUS_PUBLISHED]);

        $this->assertEquals(1, Post::status(Post::STATUS_PUBLISHED)->count());
    }

    /** @test */
    public function posts_can_be_filtered_by_private_status()
    {
        factory(Post::class)->create(['status' => Post::STATUS_PRIVATE]);

        $this->assertEquals(1, Post::status(Post::STATUS_PRIVATE)->count());
    }

    /** @test */
    public function posts_can_be_filtered_by_trashed_status()
    {
        $post = factory(Post::class)->create();
        $post->delete();

        $this->assertEquals(1, Post::status(Post::STATUS_TRASHED)->count());
    }

    /** @test */
    public function a_post_status_can_be_validated()
    {
        $this->assertTrue($this->post->isStatus(Post::STATUS_DRAFT));
    }
}
