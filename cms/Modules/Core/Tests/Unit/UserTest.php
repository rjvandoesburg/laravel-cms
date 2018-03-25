<?php

namespace Cms\Modules\Core\Tests\Unit;

use Cms\Core\Tests\TestCase;
use Cms\Modules\Core\Models\User;
use Cms\Modules\Core\Models\UserMeta;

class UserTest extends TestCase
{
    /** @test */
    public function a_user_can_have_meta()
    {
        $user = factory(User::class)->create();

        $user->update([
            'meta' => [
                'key' => 'value'
            ]
        ]);

        $this->assertEquals(1, $user->meta()->count());
    }
}