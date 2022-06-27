<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VisiosoftModuleAdvsUpdateCoorColumn extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('advs_advs', 'coor'))
        {
            Schema::table('advs_advs', function (Blueprint $table) {
                $table->longText('coor')->change();
            });
        }
    }
}
