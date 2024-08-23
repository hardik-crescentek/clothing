<div class="default-sidebar">
    <!-- Begin Side Navbar -->
    <nav class="side-navbar box-scroll sidebar-scroll">
        <!-- Begin Main Navigation -->
        <ul class="list-unstyled">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/') }}" title="Dashboard">
                    <img src="{{ url('public/uploads/sidebar/Dashboard.svg') }}" alt="Dashboard Icon">
                    <span>Dashboard</span>
                </a>
            </li>

            @role('super-admin')
            <li class="{{ (request()->is('users')) ? 'active' : '' }}">
                <a href="#dropdown-users" aria-expanded="false" data-toggle="collapse"  title="Dashboard">
                    <img src="{{ url('public/uploads/sidebar/User.svg') }}" alt="User Icon">
                    <span>Users</span>
                </a>
                <ul id="dropdown-users" class="collapse list-unstyled pt-0 {{ (request()->is('users*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('users/create')) ? 'active' : '' }}" href="{{ url('/users/create') }}" title="Add User">Add User</a></li>
                    <li><a class="{{ (request()->is('users')) ? 'active' : '' }}" href="{{ url('/users/') }}" title="User List">User List</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('clients*')) ? 'active' : '' }}">
                <a href="#dropdown-clients" aria-expanded="false" data-toggle="collapse"  title="Clients">
                    <img src="{{ url('public/uploads/sidebar/Client.svg') }}" alt="Client Icon">
                    <span>Clients</span>
                </a>
                <ul id="dropdown-clients" class="collapse list-unstyled pt-0 {{ (request()->is('clients*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('clients/create')) ? 'active' : '' }}" href="{{ url('/clients/create') }}" title="Add Client">Add Client</a></li>
                    <li><a class="{{ (request()->is('clients')) ? 'active' : '' }}" href="{{ url('/clients/') }}" title="Client List">Client List</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('supplier')) ? 'active' : '' }}">
                <a href="#dropdown-supplier" aria-expanded="false" data-toggle="collapse" title="Suppliers">
                    <img src="{{ url('public/uploads/sidebar/Suppliers.svg') }}" alt="Suppliers Icon">
                    <span>Suppliers</span>
                </a>
                <ul id="dropdown-supplier" class="collapse list-unstyled pt-0 {{ (request()->is('supplier*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('supplier/create')) ? 'active' : '' }}" href="{{ url('/supplier/create') }}" title="Add Suppliers">Add Supplier</a></li>
                    <li><a class="{{ (request()->is('supplier')) ? 'active' : '' }}" href="{{ url('/supplier/') }}" title="Suppliers List">Supplier List</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('materials*') || request()->is('category*') || request()->is('color*')) ? 'active' : '' }}">
                <a href="#dropdown-materials" aria-expanded="false" data-toggle="collapse" title="Materials">
                    <img src="{{ url('public/uploads/sidebar/Materials.svg') }}" alt="Materials Icon">
                    <span>Materials</span>
                </a>
                <ul id="dropdown-materials" class="collapse list-unstyled pt-0 {{ (request()->is('materials*') || request()->is('category*') || request()->is('color*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('materials/create')) ? 'active' : '' }}" href="{{ url('materials/create') }}" title="Add Materials">Add Material</a></li>
                    <li><a class="{{ (request()->is('materials')) ? 'active' : '' }}" href="{{ url('materials/') }}" title="Materials List">Materials List</a></li>
                    <li><a class="{{ (request()->is('category/create')) ? 'active' : '' }}" href="{{ url('/category/create') }}" title="Add Category">Add Category</a></li>
                    <li><a class="{{ (request()->is('category')) ? 'active' : '' }}" href="{{ url('/category/') }}" title="Category List">Category List</a></li>
                    {{-- <li><a class="{{ (request()->is('color/create')) ? 'active' : '' }}" href="{{ url('/color/create') }}">Add Color</a></li>
                    <li><a class="{{ (request()->is('color')) ? 'active' : '' }}" href="{{ url('/color/') }}">Color List</a></li> --}}
                </ul>
            </li>
            @endrole
            @role('client')
            <li class="{{ (request()->is('materials*') || request()->is('category*') || request()->is('color*')) ? 'active' : '' }}">
                <a href="#dropdown-materials" aria-expanded="false" data-toggle="collapse" title="Materials">
                    <img src="{{ url('public/uploads/sidebar/Materials.svg') }}" alt="Materials Icon">
                    <span>Materials</span>
                </a>
                <ul id="dropdown-materials" class="collapse list-unstyled pt-0 {{ (request()->is('materials*') || request()->is('category*') || request()->is('color*')) ? 'show' : '' }}">
                    {{-- <li><a class="{{ (request()->is('materials/create')) ? 'active' : '' }}" href="{{ url('materials/create') }}" title="Add Materials">Add Material</a></li> --}}
                    <li><a class="{{ (request()->is('materials')) ? 'active' : '' }}" href="{{ url('materials/') }}" title="Materials List">Materials List</a></li>
                    {{-- <li><a class="{{ (request()->is('category/create')) ? 'active' : '' }}" href="{{ url('/category/create') }}">Add Category</a></li> --}}
                    {{-- <li><a class="{{ (request()->is('category')) ? 'active' : '' }}" href="{{ url('/category/') }}">Category List</a></li> --}}
                    {{-- <li><a class="{{ (request()->is('color/create')) ? 'active' : '' }}" href="{{ url('/color/create') }}">Add Color</a></li>
                    <li><a class="{{ (request()->is('color')) ? 'active' : '' }}" href="{{ url('/color/') }}">Color List</a></li> --}}
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('purchase*')) ? 'active' : '' }}">
                <a href="#dropdown-purchase" aria-expanded="false" data-toggle="collapse"  title="Purchase">
                    <img src="{{ url('public/uploads/sidebar/Purchase.svg') }}" alt="Purchase Icon">
                    <span>Purchase</span>
                </a>
                <ul id="dropdown-purchase" class="collapse list-unstyled pt-0 {{ (request()->is('purchase*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('purchase')) ? 'active' : '' }}" href="{{ url('purchase/') }}" title="Purchase">Purchase</a></li>
                    <li><a class="{{ (request()->is('purchase/create')) ? 'active' : '' }}" href="{{ url('purchase/create') }}" title="Add Purchase">Add Purchase</a></li>
                    <li><a class="{{ (request()->is('purchase-item/create')) ? 'active' : '' }}" href="{{ url('purchase-item/create') }}" title="Add Purchase Item">Add Purchase Item</a></li>
                    <li><a class="{{ (request()->is('purchase/import')) ? 'active' : '' }}" href="{{ route('purchase.importt') }}" title="Import Purchase">Import Purchase</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin|client')
            <li class="{{ (request()->is('order*')) ? 'active' : '' }}">
                <a href="#dropdown-order" aria-expanded="false" data-toggle="collapse" title="Order">
                    <img src="{{ url('public/uploads/sidebar/Order.svg') }}" alt="Order Icon">
                    <span>Order</span>
                </a>
                <ul id="dropdown-order" class="collapse list-unstyled pt-0 {{ (request()->is('order*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('order')) ? 'active' : '' }}" href="{{ url('order/') }}" title="Orders">Orders</a></li>
                    <li><a class="{{ (request()->is('order/create')) ? 'active' : '' }}" href="{{ url('order/create') }}" title="Add Order">New Order</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin|client')
            <li class="{{ (request()->is('bookings')) ? 'active' : '' }}">
                <a href="{{ url('bookings/') }}" title="Bookings">
                    <img src="{{ url('public/uploads/sidebar/Booking.svg') }}" alt="Booking Icon">
                    <span>Bookings</span>
                </a>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('invoice*')) ? 'active' : '' }}">
                <a href="#dropdown-invoice" aria-expanded="false" data-toggle="collapse" title="Invoice">
                    <img src="{{ url('public/uploads/sidebar/Invoice.svg') }}" alt="Invoice Icon">
                    <span>Invoice</span>
                </a>
                <ul id="dropdown-invoice" class="collapse list-unstyled pt-0 {{ (request()->is('invoice*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('invoice')) ? 'active' : '' }}" href="{{ url('invoice/') }}" title="Invoice">Invoice</a></li>
                    <li><a class="{{ (request()->is('invoice/create')) ? 'active' : '' }}" href="{{ url('invoice/create') }}" title="Add Invoice">Add Invoice</a></li>
                </ul>
            </li>
            @endrole
            @role('payment-receiver')
            <li class="{{ (request()->is('invoice*')) ? 'active' : '' }}">
                <a href="#dropdown-invoice" aria-expanded="false" data-toggle="collapse"  title="Invoice">
                    <img src="{{ url('public/uploads/sidebar/Invoice.svg') }}" alt="Invoice Icon">
                    <span>Invoice</span>
                </a>
                <ul id="dropdown-invoice" class="collapse list-unstyled pt-0 {{ (request()->is('invoice*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('invoice')) ? 'active' : '' }}" href="{{ url('invoice/') }}" title="Invoice">Invoice</a></li>
                    {{-- <li><a class="{{ (request()->is('order/create')) ? 'active' : '' }}" href="{{ url('order/create') }}" title="Add Invoice">Add Order</a></li>                     --}}
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('inventory')) ? 'active' : '' }}">
                <a href="{{ url('inventory/') }}" title="Inventory">
                    <img src="{{ url('public/uploads/sidebar/Inventory.svg') }}" alt="Inventory Icon">
                    <span>Inventory</span>
                </a>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('return')) ? 'active' : '' }}">
                <a href="{{ url('return/') }}"  title="Return">
                    <img src="{{ url('public/uploads/sidebar/Return.svg') }}" alt="Return Icon">
                    <span>Return</span>
                </a>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('report*') || request()->is('report/best-clients*') || request()->is('payments*')) ? 'active' : '' }}">
                <a href="#dropdown-report" aria-expanded="false" data-toggle="collapse" title="Report">
                    <img src="{{ url('public/uploads/sidebar/Report.svg') }}" alt="Report Icon">
                    <span>Report</span>
                </a>
                <ul id="dropdown-report" class="collapse list-unstyled pt-0 {{ (request()->is('report*') || request()->is('report/best-clients*') || request()->is('payments*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('report/stock-report')) ? 'active' : '' }}" href="{{ url('report/stock-report/') }}" title="Stock Report">Stock Report</a></li>
                    <li><a class="{{ (request()->is('report/sales-report')) ? 'active' : '' }}" href="{{ url('report/sales-report/') }}" title="Sales Report">Sales Report</a></li>
                    <li><a class="{{ (request()->is('report/purches-report')) ? 'active' : '' }}" href="{{ url('report/purches-report/') }}" title="Purches Report">Purches Report</a></li>
                    <li><a class="{{ (request()->is('report/top-saled-material')) ? 'active' : '' }}" href="{{ url('report/top-saled-material/') }}" title="Top Saled Material">Top Saled Material</a></li>
                    @role('super-admin|payment-receiver')
                    <li>
                        <span data-target="#dropdown-payment" data-toggle="collapse" aria-expanded="false" class="custome-li-third {{ (request()->is('payments*')) ? 'active' : '' }}">
                            <i class="la la-tasks"></i><span>Payments</span>
                        </span>
                        <ul id="dropdown-payment" class="collapse list-unstyled pt-0 {{ (request()->is('payments*')) ? 'show' : ''  }}">
                            <a class="{{ (request()->is('payments/pending-payments')) ? 'active' : '' }}" href="{{ url('payments/pending-payments/') }}" title="Pending Payments">Pending Payments</a></li>
                            <a class="{{ (request()->is('payments/received-payments')) ? 'active' : '' }}" href="{{ url('payments/received-payments/') }}" title="Received Payments">Received Payments</a></li>
                        </ul>
                    </li>
                    @endrole
                    <li>
                        <span data-target="#dropdown-client" data-toggle="collapse" aria-expanded="false" class="custome-li-third {{ (request()->is('report/best-clients*')) ? 'active' : '' }}">
                            <i class="la la-tasks"></i><span>Best Client</span>
                        </span>
                        <ul id="dropdown-client" class="collapse list-unstyled pt-0 {{ (request()->is('report/best-clients/*')) ? 'show' : '' }}">
                            <a class="{{ (request()->is('report/best-clients/by-order')) ? 'active' : '' }}" href="{{ url('report/best-clients/by-order') }}" title="By Order">By Order</a></li>
                            <a class="{{ (request()->is('report/best-clients/by-cost')) ? 'active' : '' }}" href="{{ url('report/best-clients/by-cost') }}" title="By Cost">By Cost</a></li>
                        </ul>
                    </li>
                    <li><a class="{{ (request()->is('report/sended-material')) ? 'active' : '' }}" href="{{ url('report/sended-material/') }}" title="Material Sended To Client's">Material Sended To Client's</a></li>
                </ul>
            </li>
            @endrole
            @role('super-admin')
            <li class="{{ (request()->is('audit')) ? 'active' : '' }}">
                <a href="{{ url('audit/') }}" title="Audit">
                    <img src="{{ url('public/uploads/sidebar/Audit.svg') }}" alt="Audit Icon">
                    <span>Audit</span>
                </a>
            </li>
            @endrole
            @role('warehouse')
            <li class="{{ (request()->is('order*')) ? 'active' : '' }}">
                <a href="#dropdown-order" aria-expanded="false" data-toggle="collapse" title="Order">
                    <img src="{{ url('public/uploads/sidebar/Order.svg') }}" alt="Order Icon" title="Order">
                    <span>Order</span>
                </a>
                <ul id="dropdown-order" class="collapse list-unstyled pt-0 {{ (request()->is('order*')) ? 'show' : '' }}">
                    <li><a class="{{ (request()->is('order')) ? 'active' : '' }}" href="{{ url('order/') }}" title="Orders">Orders</a></li>
                </ul>
            </li>
            @endrole
            <!-- <li><a href="components-widgets.html"><i class="la la-spinner"></i><span>Widgets</span></a></li> -->
        </ul>
        <!-- End Main Navigation -->
    </nav>
    <!-- End Side Navbar -->
</div>
<style>
.custome-li-third {
    font-size: 0.85rem !important;
    padding: 10px 10px 10px 40px !important;
    text-decoration: none !important;
    display: block;
    font-weight: 500 !important;
    position: relative;
    cursor: pointer;
}

.custome-li-third::before {
    color: #fff8eb;
    font-size: 0.85rem;
    content: '\f124';
    display: inline-block;
    transform: translateY(-50%);
    font-family: 'ionicons';
    position: absolute;
    top: 50%;
    right: 20px;
    opacity: 0.5;
}

.custome-li-third[aria-expanded="true"]::before {
    content: '\f123';
}

.side-navbar.box-scroll.sidebar-scroll img {
    width: 7%; /* Reduce size by 20% */
    transition: width 0.3s ease; /* Optional: add a transition effect */
}

.side-navbar.box-scroll.sidebar-scroll.shrinked img {
    width: 30%; /* Reset size to original */
}

</style>
