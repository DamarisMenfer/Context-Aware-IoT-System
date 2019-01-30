package com.ramotion.foldingcell.examples.listview;

import android.content.Context;
import android.support.annotation.NonNull;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.ramotion.foldingcell.FoldingCell;
import com.ramotion.foldingcell.examples.R;

import java.util.HashSet;
import java.util.List;

/**
 * Simple example of ListAdapter for using with Folding Cell
 * Adapter holds indexes of unfolded elements for correct work with default reusable views behavior
 */
@SuppressWarnings({"WeakerAccess", "unused"})
public class FoldingCellListAdapter extends ArrayAdapter<Item> {

    private HashSet<Integer> unfoldedIndexes = new HashSet<>();
    private View.OnClickListener defaultRequestBtnClickListener;

    public FoldingCellListAdapter(Context context, List<Item> objects) {
        super(context, 0, objects);
    }

    @NonNull
    @Override
    public View getView(int position, View convertView, @NonNull ViewGroup parent) {
        // get item for selected view
        Item item = getItem(position);
        // if cell is exists - reuse it, if not - create the new one from resource
        FoldingCell cell = (FoldingCell) convertView;
        ViewHolder viewHolder;
        if (cell == null) {
            viewHolder = new ViewHolder();
            LayoutInflater vi = LayoutInflater.from(getContext());
            cell = (FoldingCell) vi.inflate(R.layout.cell, parent, false);
            // binding view parts to view holder
            viewHolder.day = cell.findViewById(R.id.title_price);
            viewHolder.time = cell.findViewById(R.id.title_time_label);
            viewHolder.date = cell.findViewById(R.id.title_date_label);
            viewHolder.startTime = cell.findViewById(R.id.title_from_address);
            viewHolder.finishTime = cell.findViewById(R.id.title_to_address);
            viewHolder.title = cell.findViewById(R.id.title_class);
            viewHolder.contentRequestBtn = cell.findViewById(R.id.content_request_btn);

            viewHolder.timeCellTitle = cell.findViewById(R.id.title_time_cell);
            viewHolder.cellTitle = cell.findViewById(R.id.group_title);
            viewHolder.subtitle1 = cell.findViewById(R.id.subtitle1);
            viewHolder.contentSubtitle1 = cell.findViewById(R.id.content_subtitle1);
            viewHolder.subtitle2 = cell.findViewById(R.id.subtitle2);
            viewHolder.contentSubtitle2 = cell.findViewById(R.id.content_subtitle2);
            viewHolder.subtitle3 = cell.findViewById(R.id.subtitle3);
            viewHolder.contentSubtitle3 = cell.findViewById(R.id.content_subtitle3);
            viewHolder.subtitle4 = cell.findViewById(R.id.subtitle4);
            viewHolder.contentSubtitle4 = cell.findViewById(R.id.content_subtitle4);
            viewHolder.downloadText = cell.findViewById(R.id.download_text);


            cell.setTag(viewHolder);
        } else {
            // for existing cell set valid valid state(without animation)
            if (unfoldedIndexes.contains(position)) {
                cell.unfold(true);
            } else {
                cell.fold(true);
            }
            viewHolder = (ViewHolder) cell.getTag();
        }

        if (null == item)
            return cell;

        // bind data from selected element to view through view holder
        viewHolder.day.setText(item.getDay());
        viewHolder.time.setText(item.getTime());
        viewHolder.date.setText(item.getDate());
        viewHolder.startTime.setText(item.getStartTime());
        viewHolder.finishTime.setText(item.getFinishTime());
        viewHolder.title.setText(item.getTitle());
        viewHolder.cellTitle.setText(item.getTitle());

        viewHolder.timeCellTitle.setText(item.getStartTime() + " - " + item.getFinishTime());
        viewHolder.subtitle1.setText(item.getSubtitle1());
        viewHolder.contentSubtitle1.setText(item.getContentSubtitle1());
        viewHolder.subtitle2.setText(item.getSubtitle2());
        viewHolder.contentSubtitle2.setText(item.getContentSubtitle2());
        viewHolder.subtitle3.setText(item.getSubtitle3());
        viewHolder.contentSubtitle3.setText(item.getContentSubtitle3());
        viewHolder.subtitle4.setText(item.getSubtitle4());
        viewHolder.contentSubtitle4.setText(item.getContentSubtitle4());


        // set custom btn handler for list item from that item
        if (item.getRequestBtnClickListener() != null) {
            viewHolder.contentRequestBtn.setOnClickListener(item.getRequestBtnClickListener());
        } else {
            // (optionally) add "default" handler if no handler found in item
            viewHolder.contentRequestBtn.setOnClickListener(defaultRequestBtnClickListener);
        }

        return cell;
    }

    // simple methods for register cell state changes
    public void registerToggle(int position) {
        if (unfoldedIndexes.contains(position))
            registerFold(position);
        else
            registerUnfold(position);
    }

    public void registerFold(int position) {
        unfoldedIndexes.remove(position);
    }

    public void registerUnfold(int position) {
        unfoldedIndexes.add(position);
    }

    public View.OnClickListener getDefaultRequestBtnClickListener() {
        return defaultRequestBtnClickListener;
    }

    public void setDefaultRequestBtnClickListener(View.OnClickListener defaultRequestBtnClickListener) {
        this.defaultRequestBtnClickListener = defaultRequestBtnClickListener;
    }

    // View lookup cache
    private static class ViewHolder {
        TextView day;
        TextView contentRequestBtn;
        TextView startTime;
        TextView finishTime;
        TextView title;
        TextView date;
        TextView time;
        TextView timeCellTitle;
        TextView cellTitle;
        TextView subtitle1;
        TextView contentSubtitle1;
        TextView subtitle2;
        TextView contentSubtitle2;
        TextView subtitle3;
        TextView contentSubtitle3;
        TextView subtitle4;
        TextView contentSubtitle4;
        TextView downloadText;

    }
}
