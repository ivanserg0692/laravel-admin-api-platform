@props(['items'])
<!-- Start block -->
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

            @include('components.crud.slots.toolbar')
            @include('components.crud.slots.table')

            @include('components.crud.slots.pagination')
        </div>
    </div>
</section>
<!-- End block -->
@include('components.crud.modals.create-product')
@include('components.crud.modals.update-product')
@include('components.crud.modals.read-product')
@include('components.crud.modals.delete-product')
