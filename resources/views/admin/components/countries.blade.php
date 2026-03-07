@php
$randomId = rand(10000,99999).time()

@endphp


<div class="{{$col_size ?? "col-md-4"}}">
    <div class="mb-3" id="cnId{{$randomId}}">
        <label class="form-label">اختر الدولة</label>
        <select name="{{$country_name ?? 'country_id'}}" id="{{$country_name ?? 'country_id'}}"
                class=" form-control"
                @if($with_cities)
                    onchange="changeCountry(this)"
                @endif


                    @if($required) required @endif
                data-fouc>
            <option value="">الدول</option>
            @foreach($countries as $Country)
                <option value="{{$Country->id}}">{{$Country->native}} - {{$Country->name}} </option>
            @endforeach
        </select>
    </div>
</div>


@if($with_cities)
<div class="{{$col_size ?? "col-md-4"}}"  style="display: none">
    <div class="mb-3" id="ctId{{$randomId}}">
        <label class="form-label">اختر المدينة</label>
        <select name="{{$city_name ?? 'city_id'}}" id="{{$city_name ?? 'city_id'}}"
                class=" form-control"  data-fouc>
            <option value="">الكل</option>
        </select>
    </div>
</div>
@endif


@once
    @push('scripts')
        <script>


          var countries =   @json($countries) ;
            function changeCountry(em) {
                var cid = $(em).val();
                var cityDiv = $(em).parent().parent().next();
                var citySelect = $(cityDiv).find('select');

                if(cid==''){
                    citySelect.empty();
                    cityDiv.hide(100);
                    return ;
                }
                var CountryId = parseInt(cid);

                var SelectedCountry = countries.find(function (Country) {
                    return Country.id === CountryId;
                });
                citySelect.empty();
                citySelect.append(new Option("الكل", ''));
                if (SelectedCountry.cities.length > 0) {
                    $.each(SelectedCountry.cities, function (index, city) {
                        citySelect.append(new Option(city.name, city.id));
                    });
                    cityDiv.show(100);
                } else {
                    cityDiv.hide(100);
                }
                citySelect.change();
            }

          document.addEventListener('DOMContentLoaded', function () {

          $('#cnId{{$randomId}}').find('select').select2();
          $('#ctId{{$randomId}}').find('select').select2();

          });

        </script>
    @endpush
@endonce


