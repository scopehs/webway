<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSiteSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SiteSetting::truncate();
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('state');
            $table->json('settings');
        });

        $new = new SiteSetting();
        $new->save();

        SiteSetting::where('id', 1)
            ->update([
                'settings->pay_gas' => 1,
                'settings->show_sig_list' => 1,
                'settings->pay_gas_amount' => 1000000,
                'settings->metro_cookie->date' => '2022-05-24 20:11:07',
                'settings->metro_cookie->cookie' => 'Fe26.2*1*4e9d0dbd70aad122cc4061805797c9ecda8d54353e7cc3ee344f9f22eacb23b8*yrtMQjZoBSvJkmTeI5GYZg*mBZV_x3-MetV3wJYEQ-sPuKw-y064nWugCttlmDlzE7yRPq6wtC0LXJGQvIVOIzd1LpZCK10JoIWDpBR356iD8NuL-Fms9d9-RFESiMnCsO38nsoiJfOB5Fu1J9qv6vZOX2HT54gCl3ntMxk4eI3rBEQKzjaszfy2kZIyg-YdhH2KNPOF6nDIZr9mpuv6SM7i98HhKo8PwI7lybuvoF82x-_S-nlL31-u8q1_TM2Zgx7p3veZNRIs-sY5c_ByTnJOBCpwIUGV8_QUTU4vMJw9c19OxA-oYNMnX1W0u78D8MvidEm5cECIorrwOh83t_Xh2cNJx0OKWCbrPCF_CRPRIjZR8hMmSBgCiaj67TkRsrK-1Yd82PME-RZqSvdj0TanLhhifqWlTH74pJMXS0fYYg6zh-wxVV7hhaev_O__nQAyAywrvX1DWOabzcghAGoy6ohAKsaR-ExGeqqGTnHjW2STucwgiwbL3KUSRHMOhv1TUA4ar3ic9RkjLGzAFHFLoys2H47LEwbD0mZAhGisKbvgX3K45x2195-OmC2VeAwP0RbRKJsRhFgpgVDtV0CDPgHUxSA58ws_sWrurkI5f2tH_l14_22HmAunj1h-smPDKnwy77Q54-QBtMol4nN-cTcPZx1Dn4VfmgeDAnhhOmClHosRjelP14kFc3_dISw4RvF1Yp4JmzR5MJGaUYIq0sL7C_9zPN0Li_z7j3X3gs7iHWPbeM2YnjBijkmOUPRoWf_IzxdMbIyv1BqGmuYQam_CLdWrNV0cHbHXzO7SJ_h1-bSrHDLxpDVaZZWXIgfBzI-9e5SANR_kZ722uTLJCiP2GIawJ0P8yjzlQGuit42bBabkD-fUl3VOevrdfYYcXutdXAs8wZ8d0eViTwJxTJ_5-54T-xkNq3YBbzjog93jebzXayenjz5Lsq7Ql4yaoxbK5tSTXbNi63ZLIxoFtHi9hyLM1k-odUX_nbOCOUsWFpiXdvmOje_MgeAKIak9LTvoF84kTIUu_I11whQROLqfcounSZsl_WJNyjsDJ_jOCOk6JP0xJs4GuQcLBV9GsAU7CjRKOkvCcF4e96_iRaYDcYijLLU_ZuCFG34bhI08CLacp3KkmuySHgrvvcvwN-xh9ADnd8wEA9v3cKUveeu9BTnd-5Xfz1R9I5Z9w7YUqHpS9PWxJSNq7g2xeZZVRaHaWILgc69ZN5QsIoWyPCfLYm4R2gWHIs5V_Hs9TL4b04ZMb_0aRzWLpH0LW39wlTAPdAxTWY0yBtUX72P-NBj8nD_CCf_5yFEPJFinvsNmDwmdgWqLp67OUQUab7OWADu-Dkej3W3An_3YxWEuFuG08JqxkxYdrBT6GvsWd_taLtuvISE8mVAPra4C2qVXGh0LJNrl9lanO8vXxR1DafKF1igl2uAqqKRKkdQnEWD4c6XBOiqT6YDBlzbi2hQG2zlkVwMTALVgOd1vBUuKZLwcOgsTpKo0SUYF1JbJGd8uft6yQe52Kot3huSzR18YXrlJFcvbwWHYLYbHQ_9nR6Agy-kEemFVEy1zl1vZsiaIZZ87N5KB6tOfjKZYUj2m2dJ9cBwCIPLNYq-RP_FRYBMvtnQVW8OcBfn4CVLmw*1654719046075*ce4d04b04ab57545ec14cbc28f5275a9f1ea3bbc5648144afd3e9317db909149*5A_35MErCc4fiSJp9bsnI6R9vB_ojflr_59u-GFW6D8',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
