<?php

use App\Constant\ActionStateEnum;
use App\Models\Action;
use App\Models\Agent;
use App\Models\Odd;
use App\Models\OperationalObjective;
use App\Models\Partner;
use App\Models\Service;
use App\Models\StrategicObjective;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('strategic_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('operational_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StrategicObjective::class)->constrained('strategic_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OperationalObjective::class)->constrained('operational_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->date('due_date')->nullable();
            $table->text('budget_estimate')->nullable();
            $table->text('financing_mode')->nullable();
            $table->enum('progress_indicator', ActionStateEnum::toArray())->default(ActionStateEnum::NEW->value);
            $table->text('work_plan')->nullable();
            $table->text('evaluation_indicator')->nullable();
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable(true);
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('action_agent', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Agent::class)->constrained('agents')->cascadeOnDelete();
        });

        Schema::create('action_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Partner::class)->constrained('partners')->cascadeOnDelete();
        });

        Schema::create('action_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained('services')->cascadeOnDelete();
        });

        Schema::create('action_odd', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Odd::class)->constrained('odds')->cascadeOnDelete();
        });

        Schema::create('agent_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Agent::class)->constrained('agents')->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained('services')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_odd');
        Schema::dropIfExists('action_service');
        Schema::dropIfExists('action_partner');
        Schema::dropIfExists('action_agent');
        Schema::dropIfExists('agent_service');
        Schema::dropIfExists('odds');
        Schema::dropIfExists('services');
        Schema::dropIfExists('politicians');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('operational_objectives');
        Schema::dropIfExists('strategic_objectives');
    }
};
