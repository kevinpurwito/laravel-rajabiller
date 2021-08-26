<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbItemsTable extends Migration
{
    protected function getTable()
    {
        return config('kp_rajabiller.table_names.items', 'rb_items');
    }

    public function up()
    {
        $tableName = $this->getTable();

        if (! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();

                if (config('kp_rajabiller.popular_column', true)) {
                    // additional column to enable you to load and show popular countries first
                    $table->boolean('popular')->default(false);
                }

                if (config('kp_rajabiller.ordinal_column', true)) {
                    // additional column to enable you to set which countries shown first
                    $table->unsignedSmallInteger('ordinal')->default(999);
                }

                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->getTable());
    }
}
