
<li>
    <a class="J_menuItem" href="{{route('backend.school.index')}}"><i class="fa fa-bars"></i> <span class="nav-label">园所管理</span></a>
</li>

<li>
    <a href="#">
        <i class="fa fa-bars"></i>
        <span class="nav-label">会员管理</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li><a class="J_menuItem" href="{{route('backend.customer.index')}}">家长信息</a></li>
        <li><a class="J_menuItem" href="{{route('backend.kid.index')}}">小朋友信息</a></li>

    </ul>
</li>


<li>
    <a href="#">
        <i class="fa fa-bars"></i>
        <span class="nav-label">借阅管理</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li><a class="J_menuItem" href="{{route('backend.apply.index')}}">配送/回收</a></li>
        <li><a class="J_menuItem" href="{{route('backend.borrow.index')}}">借阅查询</a></li>

    </ul>
</li>

<li>
    <a href="#">
        <i class="fa fa-bars"></i>
        <span class="nav-label">图书管理</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li><a class="J_menuItem" href="{{route('backend.book_category.index')}}">分类管理</a></li>
        <li><a class="J_menuItem" href="{{route('backend.book.index')}}">图书列表</a></li>
        <li><a class="J_menuItem" href="{{route('backend.book.stocks')}}">图书设置</a></li>
        <li><a class="J_menuItem" href="{{route('backend.barcode.index')}}">条码生成</a></li>

    </ul>
</li>

<li>
    <a class="J_menuItem" href="{{route('backend.banner.index')}}"><i class="fa fa-bars"></i> <span class="nav-label">Banner管理</span></a>
</li>

<li>
    <a href="#">
        <i class="fa fa-bars"></i>
        <span class="nav-label">账务管理</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li><a class="J_menuItem" href="{{route('backend.vipcard.buy')}}">购买记录</a></li>
    </ul>
</li>

{{--<li>--}}
    {{--<a class="J_menuItem" href="{{route('backend.recharges.index')}}"><i class="fa fa-bars"></i> <span class="nav-label">消息管理</span></a>--}}
{{--</li>--}}

<li>

    <a class="J_menuItem" href="{{route('backend.admin.index')}}"><i class="fa fa-bars"></i> <span class="nav-label">管理员</span></a>
</li>






