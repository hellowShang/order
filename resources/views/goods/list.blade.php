<table border="1">
    <tr>
        <th>商品编号</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>商品库存</th>
        <th>操作</th>
    </tr>
    @foreach($goodsInfo as $k=> $v)
        <tr>
            <td>{{$v->goods_id}}</td>
            <td>{{$v->goods_name}}</td>
            <td>{{$v->goods_price/100}}元</td>
            <td>{{$v->goods_srcoe}}件</td>
            <td>
                <a href="/cart/add/{{$v->goods_id}}">加入购物车</a>
                <a href="/goods/detail/{{$v->goods_id}}">查看</a>
            </td>
        </tr>
    @endforeach
</table>
<a href="/goods/history">查看历史记录</a>