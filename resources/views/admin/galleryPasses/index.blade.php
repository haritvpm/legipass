@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.galleryPass.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-GalleryPass">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.number') }}
                    </th>

                    <th>
                        {{ trans('cruds.galleryPass.fields.issued_date') }}
                    </th>
                    <th>
                        Accompnaying
                    </th>

                    <th>
                        {{ trans('cruds.galleryPass.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.dob') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.age') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.mobile') }}
                    </th>



                    <th>
                        {{ trans('cruds.galleryPass.fields.id_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.id_detail') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.address') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.country') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.state') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.district') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.post_office') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.pincode') }}
                    </th>
                    <th>
                        {{ trans('cruds.galleryPass.fields.photo') }}
                    </th>

                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.gallery-passes.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'number', name: 'number' },
// { data: 'person_name', name: 'person.name' },
// { data: 'person.mobile', name: 'person.mobile' },
// { data: 'person.id_detail', name: 'person.id_detail' },
{ data: 'issued_date', name: 'issued_date' },
{ data: 'num_persons', name: 'num_persons' },
// { data: 'date_of_visit', name: 'date_of_visit' },
// { data: 'guide_name', name: 'guide.name' },
// { data: 'print_count', name: 'print_count' },
{ data: 'name', name: 'name' },
{ data: 'gender', name: 'gender' },
{ data: 'dob', name: 'dob' },
{ data: 'age', name: 'age' },
{ data: 'mobile', name: 'mobile' },
{ data: 'id_type', name: 'id_type' },
{ data: 'id_detail', name: 'id_detail' },
{ data: 'address', name: 'address' },
{ data: 'country', name: 'country' },
{ data: 'state', name: 'state' },
{ data: 'district', name: 'district' },
{ data: 'post_office', name: 'post_office' },
{ data: 'pincode', name: 'pincode' },
{ data: 'photo', name: 'photo' },

// { data: 'email', name: 'email' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-GalleryPass').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
