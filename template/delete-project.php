<div class="accept-user hidden">

    <h2>Delete Project</h2>

    <i class="icon-cancel" onclick="closeDeleteProjectWindow()"></i><hr>

    <div class="accept-user-container hidden">

        <form id="delete-project-form" class="accept-user-form" method="post">

            <input type="hidden" name="projectId" value="%s"> <!-- user-id -->
            <input type="hidden" name="action" value="%s"> <!-- action -->

            <button type="submit" class="update-order-status btn-link btn-link-static">Confirm</button>

        </form>

        <button class="cancel-order update-order-status btn-link btn-link-static">Cancel</button>

    </div>

</div>