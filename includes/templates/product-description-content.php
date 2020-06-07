<div id="descriptions">
<?php foreach($description->standardized->sections as $section): ?>
    <div class="desc-section">
        <?php foreach($section->items as $item): ?>
            <div class="desc-item">
                <?php if( $item->type === "IMAGE" ): ?>
                <img src="<?php echo $item->url; ?>" srcset="<?php echo implode($item->srcset) ?>" />
                <?php else:
                echo $item->content;
                endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
</div>