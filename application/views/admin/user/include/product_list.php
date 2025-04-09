
    <tr class="item-row">
        <td width="30%">
            <input type="text" class="form-control item ws-180" placeholder="Item" type="text" name="items[]" value="<?php echo html_escape($product->name) ?>">
        </td>
        <td width="30%">
            <textarea name="details[]" class="form-control ac-textarea ws-180" rows="1" placeholder="<?php echo trans('item-description') ?>"><?php echo html_escape($product->details) ?></textarea>
        </td>
        <td width="15%">
            <input class="form-control price invo ws-100" placeholder="Price" type="text" name="price[]" value="<?php echo html_escape($product->price) ?>"> 
        </td>
        <td width="10%">
            <input class="form-control qty ws-100" placeholder="Qty" type="text" name="quantity[]" value="1">
        </td>
        <?php if ($this->business->enable_unit == 1): ?>
            <td>
                <select class="form-control units_off" name="units[]">
                    <option value="<?php echo $product->unit ?>" selected><?php echo $product->unit ?>
                </select>
            </td>
        <?php endif ?>
        <td width="15%">
            <div class="delete-btn">
                <span class="currency_wrapper"></span>
                <span class="total"><?php echo html_escape($product->price) ?></span>
                <a class="delete" href="javascript:;" title="Remove row">&times;</a>
                <input type="hidden" class="total" name="total_price[]" value="<?php echo html_escape($product->price) ?>">
                <input type="hidden" name="product_ids[]" value="<?php echo html_escape($product->id) ?>">
            </div>
        </td>
    </tr>
