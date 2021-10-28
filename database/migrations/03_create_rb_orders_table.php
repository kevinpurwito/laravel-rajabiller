<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbOrdersTable extends Migration
{
    protected function getTable()
    {
        return config('kp_rajabiller.table_names.orders', 'rb_orders');
    }

    public function up()
    {
        $tableName = $this->getTable();

        if (! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('rb_item_id')->constrained()->nullable()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->string('code', 20)->nullable();
                $table->string('sn', 20)->nullable();
                $table->string('uid', 10)->nullable();
                $table->string('env', 10)->nullable();
                $table->string('status', 20)->nullable();
                $table->unsignedInteger('amount')->default(0);
                $table->string('item_code')->nullable();
                $table->string('time')->nullable();
                $table->string('customer_id_1')->nullable();
                $table->string('customer_id_2')->nullable();
                $table->string('customer_id_3')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('period')->nullable();
                $table->string('ref_1')->nullable();
                $table->string('ref_2')->nullable();
                $table->string('ref_3')->nullable();
                $table->string('receipt_url')->nullable();
                $table->string('note')->nullable();
                $table->string('detail')->nullable();
                $table->unsignedInteger('balance_deducted')->default(0);
                $table->unsignedInteger('balance_remaining')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->getTable());
    }
}
