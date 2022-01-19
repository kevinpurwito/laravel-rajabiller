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
                $table->unsignedBigInteger('rb_group_id')->nullable();
                $table->boolean('is_active')->index()->default(1);
                $table->string('code', 20)->index();
                $table->string('name')->nullable();
                $table->string('type', 30)->nullable()->index()->comment('BILLS|TOP UP');
                $table->string('subtype', 30)->nullable()->index()->comment('RbSubtype.php');
                $table->string('group_name', 30)->nullable()->index()->comment('rb_groups');

                $table->unsignedInteger('denominator')->default(0);
                $table->double('price')->default(0);
                $table->double('fee')->default(0);
                $table->double('commission')->default(0);

                if (config('kp_rajabiller.popular_column', true)) {
                    // additional column to enable you to load and show popular countries first
                    $table->boolean('popular')->default(false);
                }

                if (config('kp_rajabiller.ordinal_column', true)) {
                    // additional column to enable you to set which countries shown first
                    $table->unsignedSmallInteger('ordinal')->default(999);
                }

                $table->timestamps();

                $table->foreign('rb_group_id')->references('id')->on('rb_groups')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->getTable());
    }
}
