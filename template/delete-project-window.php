<div class="delete-project hidden">

    <h2>Delete project</h2>

    <i class="icon-cancel" onclick="closeRemoveProjectBox()"></i><hr>

    <div class="delete-project-container hidden">

        <div class="delete-error-message" style="color: red; margin-bottom: 15px;"></div>

        <form id="deleteForm" class="delete-project-form" method="post">

            <input type="hidden" id="project-id" name="project-id" value="%s"> <!-- project-id -->

            <button type="submit" class="update-order-status btn-link btn-link-static">Confirm</button>

        </form>

        <button class="cancel-order update-order-status btn-link btn-link-static">Cancel</button>

    </div>

</div>