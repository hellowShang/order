<table border="1">
    <tr>
        <th>商品编号</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>商品库存</th>
    </tr>
    @foreach($goodsInfo as $k=> $v)
        <tr>
            <td>{{$v['goods_id']}}</td>
            <td>{{$v['goods_name']}}</td>
            <td>{{$v['goods_price']/100}}元</td>
            <td>{{$v['goods_srcoe']}}件</td>
        </tr>
    @endforeach
</table>