

    <link href="{{ asset('css/styles_sidebar.css') }}" rel="stylesheet">

<div class="flex-shrink-0 p-3" style="width: 280px;">
    <a  class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
        <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-5 fw-semibold">Menu</span>
    </a>
    <ul class="list-unstyled ps-0">
        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#organisation-collapse" aria-expanded="true">
                Organisation
            </button>
            <div class="collapse" id="organisation-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/organisations" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Organisations</a></li>
                    <li><a href="/employees" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Employees</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="true">
                Orders
            </button>
            <div class="collapse" id="orders-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/orders" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Orders</a></li>
                    <li><a href="/customers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Customers</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#warehouse-collapse" aria-expanded="false">
                Warehouse management
            </button>
            <div class="collapse" id="warehouse-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/suppliers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Suppliers</a></li>
                    <li><a href="/warehouses" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Warehouses</a></li>
                    <li><a href="/items" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Items</a></li>
                    <li><a href="/animal_numbers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Animal numbers</a></li>
                    <li><a href="/animals" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Animals</a></li>
                    <li><a href="/supply_numbers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Supply numbers</a></li>
                    <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Supplies</a></li>
                </ul>
            </div>
        </li>
        <li class="border-top my-3"></li>
    </ul>
</div>

