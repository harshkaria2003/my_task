<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsTableAddUniqueAndPaidAt extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->unique('transaction_id');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['transaction_id']);
            $table->dropColumn('paid_at');
        });
    }
}
