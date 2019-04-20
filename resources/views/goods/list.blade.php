<table border="1">
    <tr>
        <td>商品编号</td>
        <td>商品名称</td>
        <td>商品价格</td>
        <td>商品库存</td>
        <td>操作</td>
    </tr>
    @foreach($goodsInfo as $k=> $v)
        <tr>
            <td>{{$v->goods_id}}</td>
            <td>{{$v->goods_name}}</td>
            <td>{{$v->goods_price/100}}元</td>
            <td>{{$v->goods_srcoe}}件</td>
            <td><a href="/cart/add/{{$v->goods_id}}">加入购物车</a></td>
        </tr>
    @endforeach
</table>