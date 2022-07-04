<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div id="select-all-alert" class="alert alert-warning alert-dismissible fade hidden fixed-bottom" role="alert">
    <strong>All 10 conversations on this page have been selected.</strong> 
    &nbsp&nbsp<a href="#" class="alert-link" id="select-all-button"><span id="button-text">Select all conversations that match this search</span></a>
</div>

<div id="select-clear-alert" class="alert alert-warning alert-dismissible fade hidden fixed-top" role="alert">
    <strong id="msg">All conversations in this search have been select!</strong> 
    &nbsp&nbsp<a href="#" class="alert-link" id="select-clear-button"><span id="button-text">clear selection</span></a>
</div>


<div id="page-wrapper">
    <div class="row"> 
        <div class="col-lg-12"> 
            <button class="btn btn-primary" id="export-to-csv">导出到CSV文件</button>
        </div> 
    </div>
    <div class="row">
        <div class="col-lg-12">

<?php
echo GridView::widget([
    'id' => 'mySupplierGridView',
    'dataProvider' => $provider,
    'filterModel' => $model,
    'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],
        [
            'label' => 'ID',
            'attribute' => 'id',
            'format' => 'raw',
            'headerOptions' => [
                'style' => 'width:120px;',
            ],
            'filter' => ['>' => '>10', '<' => '<10', '<=' => '<=10', '>=' => '>=10', '='=> '=10', '<>' => '<>10'],

        ],
        [
            'label' => '名称',
            'attribute' => 'name',
            'format' => 'raw',
        ],
        [
            'label' => '编码',
            'attribute' => 'code',
            'format' => 'raw',
        ],
        [
            'label' => '状态',
            'filter' => ['ok' => 'ok', 'hold' => 'hold'],
            'attribute' => 't_status',
            'format' =>  'raw',
            'value' =>  function ($data) {
                return ($data->t_status == 'ok') ? 'ok' : (($data->t_status == 'hold') ? 'hold' : '');
            }
        ],
    ],
]);
?>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">

var supplier_idz = [];
$('.select-on-check-all').change(function(e) {
    var checked = $(this).is(':checked');
    if (true === checked) {

        supplier_idz = $("#mySupplierGridView").yiiGridView('getSelectedRows');
        $("#select-all-alert").addClass("show");
        $("#select-clear-alert").removeClass("show");
    } else {
        $("#select-all-alert").removeClass("show");
        $("#select-clear-alert").removeClass("show");
        supplier_idz = [];
    }
});

$("#select-all-button").on("click", function() {
    var id_opreation = $("select[name='Supplier[id]']").val();
    var name = $("input[name='Supplier[name]']").val();
    var code = $("input[name='Supplier[code]']").val();
    var t_status = $("select[name='Supplier[t_status]']").val();

    $.ajax({
        url: "/index.php?r=supplier/all-list"
            + "&Supplier[id]=" + id_opreation
            + "&Supplier[name]=" + name
            + "&Supplier[code]=" + code 
            + "&Supplier[t_status]=" + t_status 
            
    }).done(function(req) {
        supplier_idz = JSON.parse(req)
        console.log(supplier_idz);
        $("#select-all-alert").removeClass("show");
        $("#select-clear-alert").addClass("show");
    });
});

$("#select-clear-button").on("click", function() {
    supplier_idz = [];
    $("#select-all-alert").removeClass("show");
    $("#select-clear-alert").removeClass("show");
    $("#mySupplierGridView").find(":checkbox").prop('checked', false)
});

$("#export-to-csv").on("click", function() {
    if (supplier_idz.length == 0) {
        supplier_idz = $("#mySupplierGridView").yiiGridView('getSelectedRows');
    }
    if (supplier_idz.length == 0) {
        alert('请选择要导出的数据');
        return;
    }
    console.log(supplier_idz);
    // 下载链接
    window.location.href = 'index.php?r=supplier/export&idz=' + supplier_idz.join(',')
});
</script>
