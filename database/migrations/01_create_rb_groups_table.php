<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbGroupsTable extends Migration
{
    protected function getTable()
    {
        return config('kp_rajabiller.table_names.groups', 'rb_groups');
    }

    public function up()
    {
        $tableName = $this->getTable();

        if (! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('is_active')->index()->default(1);
                $table->string('type', 30)->nullable()->index()->comment('bills|public|top_up');
                $table->string('subtype', 30)->nullable()->index()->comment('RbSubtype.php');
                $table->string('name');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->getTable());
    }
}
