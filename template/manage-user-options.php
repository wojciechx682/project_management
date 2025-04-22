<div class="accept-user hidden">

    <h2>Accept or Reject user</h2>

    <i class="icon-cancel"></i><hr>

    <div class="accept-user-container hidden">

        <form class="accept-user-form" action="remove-order.php" method="post">

            <input type="hidden" name="user-id" value="%s"> <!-- order-id -->

            <button type="submit" class="update-order-status btn-link btn-link-static">Confirm</button>

        </form>

        <button class="cancel-order update-order-status btn-link btn-link-static">Cancel</button>

    </div> <!-- .delivery-date -->

</div> <!-- .update-status -->