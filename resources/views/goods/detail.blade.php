<table border="1">
    <tr>
        <th>商品编号</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>商品库存</th>
        <th>是否上架</th>
        <th>点击量</th>
    </tr>
        <tr>
            <td>{{$detail->goods_id}}</td>
            <td>{{$detail->goods_name}}</td>
            <td>{{$detail->goods_price/100}}元</td>
            <td>{{$detail->goods_srcoe}}件</td>
            <td>@if($detail->is_up == 1) 已上架 @else 已下架 @endif</td>
            <td>{{$detail->hot}}</td>
        </tr>
</table>