<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Navigation</h3>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="{{ (Request::is('admin/users*') ? 'active' : '') }}"><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> Users</a></li>
            <li class="{{ (Request::is('admin/gifts*') ? 'active' : '') }}"><a href="{{ route('admin.gifts.index') }}"><i class="fa fa-gift"></i> Gifts</a></li>
            <li class="{{ (Request::is('admin/cashout*') ? 'active' : '') }}"><a href="{{ route('admin.cashout.index') }}"><i class="fa fa-money"></i> Cashout requests</a></li>
        </ul>
    </div>
    <!-- /.box-body -->
</div>