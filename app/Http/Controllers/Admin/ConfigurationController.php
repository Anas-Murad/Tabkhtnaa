<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Country;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function choose_country(Request $request, $classification)
    {


        return view('admin.configuration.choose-country', compact('classification'));
    }

    public function edit(Request $request, $classification)
    {

        $country = Country::findOrFail($request->country_id);

        if ($classification != 'all') {
            $config = Configuration::where('country_id', $country->id)->whereClassification($classification)->get();
            $ConfigurationData = (ConfigurationData()[$classification]);

            $initForm = [];
            foreach ($ConfigurationData as $item) {
                $find = $config->where('config_key', $item['config_key'])->first();
                if ($find) {
                    $item['config_value'] = $find->config_value;
                }
                $initForm[] = $item;
            }


        } else {
            $config = Configuration::where('country_id', $country->id)->get()->groupBy('classification');
            $ConfigurationData = (ConfigurationData());
            $initForm = [];
            foreach ($ConfigurationData as $k => $itemArr) {
                foreach ($itemArr as $item) {
                    if (isset($config[$k])) {
                        $find = $config[$k]->where('config_key', $item['config_key'])->first();
                        if ($find) {
                            $item['config_value'] = $find->config_value;
                        }
                    }
                    $initForm[$k][] = $item;
                }
            }

        }

        unset($config);
        unset($ConfigurationData);
        return view('admin.configuration.edit', compact(
            'classification',
            'initForm',
            'country',
        ));
    }


    public function update(Request $request, $classification, $country_id)
    {
        $country = Country::findOrFail($country_id);
        $data = $request->except('_token');


        if ($classification != 'all') {
            foreach ($data as $config_key => $config_value) {
                Configuration::updateOrCreate(
                    [
                        'country_id' => $country_id,
                        'config_key' => $config_key,
                        'classification' => $classification,
                    ],
                    [
                        'country_id' => $country_id,
                        'config_key' => $config_key,
                        'classification' => $classification,
                        'config_value' => $config_value,
                    ]
                );
            }


        } else {

            foreach ($data as $classification => $dataArr) {
                foreach ($dataArr as $config_key => $config_value) {

                    Configuration::updateOrCreate(
                        [
                            'country_id' => $country_id,
                            'config_key' => $config_key,
                            'classification' => $classification,
                        ],
                        [
                            'country_id' => $country_id,
                            'config_key' => $config_key,
                            'classification' => $classification,
                            'config_value' => $config_value,
                        ]
                    );
                }
            }


        }

        session()->flash('Success', 'تم تعديل  بنجاح ');
        return redirect()->back();
    }

    public function destroy(Configuration $configuration)
    {
        $configuration->delete();

        return response()->json();
    }
}
