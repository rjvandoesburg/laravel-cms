<?php

namespace Cms\Modules\Core\Tests\Unit;

use Cms\Core\Tests\TestCase;
use Cms\Modules\Core\Models\User;
use Cms\Modules\Core\Models\UserMeta;

class MetaTest extends TestCase
{
    /**
     * @var \Cms\Modules\Core\Models\User
     */
    protected $user;

    /**
     * @var \Cms\Modules\Core\Models\UserMeta
     */
    protected $userMetaModel;

    /**
     * This method is called before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->userMetaModel = (new UserMeta);
    }


    /** @test */
    public function meta_with_an_empty_string_value_is_not_saved()
    {
        UserMeta::create([
            'user_id' => $this->user->id,
            'key' => 'test_meta',
            'value' => ''
        ]);

        $this->assertDatabaseMissing($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => 'test_meta',
        ]);
    }

    /** @test */
    public function meta_with_a_null_value_is_not_saved()
    {
        UserMeta::create([
            'user_id' => $this->user->id,
            'key' => 'test_meta',
            'value' => null
        ]);

        $this->assertDatabaseMissing($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => 'test_meta',
        ]);
    }

    /** @test */
    public function meta_with_data_is_saved()
    {
        UserMeta::create([
            'user_id' => $this->user->id,
            'key' => 'test_meta',
            'value' => 'Testvalue'
        ]);

        $this->assertDatabaseHas($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => 'test_meta',
        ]);
    }

    /** @test */
    public function meta_updated_with_an_empty_string_is_deleted()
    {
        /** @var \Cms\Modules\Core\Models\UserMeta $meta */
        $meta = factory(UserMeta::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
        ]);

        $meta->update([
            'value' => ''
        ]);

        $this->assertDatabaseMissing($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
        ]);
    }

    /** @test */
    public function meta_updated_with_a_null_value_is_deleted()
    {
        /** @var \Cms\Modules\Core\Models\UserMeta $meta */
        $meta = factory(UserMeta::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
        ]);

        $meta->update([
            'value' => null
        ]);

        $this->assertDatabaseMissing($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
        ]);
    }

    /** @test */
    public function meta_with_data_is_updated()
    {
        /** @var \Cms\Modules\Core\Models\UserMeta $meta */
        $meta = factory(UserMeta::class)->create([
            'user_id' => $this->user->id,
            'value' => 'value one'
        ]);

        $this->assertDatabaseHas($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
            'value' => 'value one'
        ]);

        $meta->update([
            'value' => 'value two'
        ]);

        $this->assertDatabaseMissing($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
            'value' => 'value one'
        ]);

        $this->assertDatabaseHas($this->userMetaModel->getTable(), [
            'user_id' => $this->user->id,
            'key' => $meta->key,
            'value' => 'value two'
        ]);
    }
}