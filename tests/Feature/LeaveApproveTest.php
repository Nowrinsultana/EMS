<?php

namespace Tests\Feature;

use App\Enums\LeaveStatus;
use App\Models\Department;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveApproveTest extends TestCase
{
    use RefreshDatabase;

    public function test_sets_up_factories(): void
    {
        $dept = Department::factory()->create();
        $admin = User::factory()->create([
            'isadmin' => true,
            'department_id' => $dept->id,
        ]);
        $staff = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $leave = Leave::factory()->create([
            'department_id' => $dept->id,
            'staff_id' => $staff->id,
            'status' => LeaveStatus::Pending,
        ]);

        $this->assertTrue($leave->exists);
        $this->assertEquals('pending', $leave->status->value);
    }

    public function test_approve_leave(): void
    {
        $dept = Department::factory()->create();
        $admin = User::factory()->create([
            'isadmin' => true,
            'department_id' => $dept->id,
        ]);
        $staff = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $leave = Leave::factory()->create([
            'department_id' => $dept->id,
            'staff_id' => $staff->id,
            'status' => LeaveStatus::Pending,
        ]);

        $this->actingAs($admin);

        $response = $this->put(route('leave.approve', ['dptid' => $dept->id, 'leave' => $leave->id]));

        $response->assertSessionHas('status');
        $this->assertEquals('approved', $leave->fresh()->status->value);
    }

    public function test_decline_leave(): void
    {
        $dept = Department::factory()->create();
        $admin = User::factory()->create([
            'isadmin' => true,
            'department_id' => $dept->id,
        ]);
        $staff = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $leave = Leave::factory()->create([
            'department_id' => $dept->id,
            'staff_id' => $staff->id,
            'status' => LeaveStatus::Pending,
        ]);

        $this->actingAs($admin);

        $response = $this->put(route('leave.decline', ['dptid' => $dept->id, 'leave' => $leave->id]));

        $response->assertSessionHas('status');
        $this->assertEquals('declined', $leave->fresh()->status->value);
    }

    public function test_edit_leave_page(): void
    {
        $dept = Department::factory()->create();
        $admin = User::factory()->create([
            'isadmin' => true,
            'department_id' => $dept->id,
        ]);
        $staff = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $leave = Leave::factory()->create([
            'department_id' => $dept->id,
            'staff_id' => $staff->id,
            'status' => LeaveStatus::Pending,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('leave.edit', ['dptid' => $dept->id, 'leave' => $leave->id]));

        $response->assertOk();
        $response->assertViewIs('leave.edit');
    }
}
