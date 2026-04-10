<div class="container mt-4">
<h3>Cart</h3>

<?php $cart = $this->session->userdata('cart'); ?>

<?php if(empty($cart)): ?>
<p>Cart is empty</p>
<?php else: ?>

<table class="table">
<tr>
<th>Name</th><th>Qty</th><th>Price</th><th>Total</th><th></th>
</tr>

<?php foreach($cart as $id=>$item): ?>
<tr>
<td><?= $item['name'] ?></td>

<td>
<button class="btn btn-sm btn-success update" data-type="inc" data-id="<?= $id ?>">+</button>
<?= $item['qty'] ?>
<button class="btn btn-sm btn-danger update" data-type="dec" data-id="<?= $id ?>">-</button>
</td>

<td><?= $item['price'] ?></td>
<td><?= $item['price']*$item['qty'] ?></td>

<td>
<button class="btn btn-danger remove" data-id="<?= $id ?>">X</button>
</td>
</tr>
<?php endforeach; ?>

</table>

<h4>Total: ₹<?= cart_total($cart) ?></h4>

<form id="checkoutForm">
<input name="name" class="form-control mb-2" placeholder="Name">
<input name="mobile" class="form-control mb-2" placeholder="Mobile">
<input name="email" class="form-control mb-2" placeholder="Email">

<button class="btn btn-success">Checkout</button>
</form>

<?php endif; ?>
</div>

<script>
$('.update').click(function(){
    $.post("<?= base_url('cart/update') ?>",{
        id:$(this).data('id'),
        type:$(this).data('type')
    },()=>location.reload());
});

$('.remove').click(function(){
    $.post("<?= base_url('cart/remove') ?>",{id:$(this).data('id')},()=>location.reload());
});

$('#checkoutForm').submit(function(e){
e.preventDefault();

$.post("<?= base_url('cart/checkout') ?>",$(this).serialize(),function(res){
let r = JSON.parse(res);

if(r.status){
    window.location = "<?= base_url('cart/success/') ?>"+r.order_id;
}else{
    Swal.fire('Error', r.errors || r.msg, 'error');
}
});
});
</script>