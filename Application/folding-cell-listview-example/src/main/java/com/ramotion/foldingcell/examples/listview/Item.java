package com.ramotion.foldingcell.examples.listview;

import android.view.View;

import java.util.ArrayList;

/**
 * Simple POJO model for example
 */
@SuppressWarnings({"unused", "WeakerAccess"})
public class Item {

    private String day;
    private String startTime;
    private String finishTime;
    private String title;
    private String date;
    private String time;

    private String subtitle1;
    private String contentSubtitle1;
    private String subtitle2;
    private String contentSubtitle2;
    private String subtitle3;
    private String contentSubtitle3;
    private String subtitle4;
    private String contentSubtitle4;
    private String downloadText;

    private View.OnClickListener requestBtnClickListener;

    public Item() {
    }

    public Item(String day, String startTime, String finishTime, String title, String date, String time, String subtitle1, String contentSubtitle1, String subtitle2, String contentSubtitle2, String subtitle3, String contentSubtitle3, String subtitle4, String contentSubtitle4, String downloadText) {
        this.day = day;
        this.startTime = startTime;
        this.finishTime = finishTime;
        this.title = title;
        this.date = date;
        this.time = time;

        this.subtitle1 = subtitle1;
        this.contentSubtitle1 = contentSubtitle1;
        this.subtitle2 = subtitle2;
        this.contentSubtitle2 = contentSubtitle2;
        this.subtitle3 = subtitle3;
        this.contentSubtitle3 = contentSubtitle3;
        this.subtitle4 = subtitle4;
        this.contentSubtitle4 = contentSubtitle4;

        this.downloadText = downloadText;
    }

    public String getDay() {
        return day;
    }

    public void setDay(String day) {
        this.day = day;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getStartTime() {
        return startTime;
    }

    public void setStartTime(String startTime) {
        this.startTime = startTime;
    }

    public String getFinishTime() {
        return finishTime;
    }

    public void setFinishTime(String finishTime) {
        this.finishTime = finishTime;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getSubtitle1() {
        return subtitle1;
    }

    public void setSubtitle1(String subtitle1) {
        this.subtitle1 = subtitle1;
    }

    public String getContentSubtitle1() {
        return contentSubtitle1;
    }

    public void setContentSubtitle1(String contentSubtitle1) {
        this.contentSubtitle1 = contentSubtitle1;
    }

    public String getSubtitle2() {
        return subtitle2;
    }

    public void setSubtitle2(String subtitle2) {
        this.subtitle2 = subtitle2;
    }

    public String getContentSubtitle2() {
        return contentSubtitle2;
    }

    public void setContentSubtitle2(String contentSubtitle2) {
        this.contentSubtitle2 = contentSubtitle2;
    }

    public String getSubtitle3() {
        return subtitle3;
    }

    public void setSubtitle3(String subtitle3) {
        this.subtitle3 = subtitle3;
    }

    public String getContentSubtitle3() {
        return contentSubtitle3;
    }

    public void setContentSubtitle3(String contentSubtitle3) {
        this.contentSubtitle3 = contentSubtitle3;
    }

    public String getSubtitle4() {
        return subtitle4;
    }

    public void setSubtitle4(String subtitle4) {
        this.subtitle4 = subtitle4;
    }

    public String getContentSubtitle4() {
        return contentSubtitle4;
    }

    public void setContentSubtitle4(String contentSubtitle4) {
        this.contentSubtitle4 = contentSubtitle4;
    }

    public String getDownloadText() {
        return downloadText;
    }

    public void setDownloadText(String downloadText) {
        this.downloadText = downloadText;
    }

    public View.OnClickListener getRequestBtnClickListener() {
        return requestBtnClickListener;
    }

    public void setRequestBtnClickListener(View.OnClickListener requestBtnClickListener) {
        this.requestBtnClickListener = requestBtnClickListener;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;

        Item item = (Item) o;

        if (day != null ? !day.equals(item.day) : item.day != null) return false;
        if (title != null ? !title.equals(item.title) : item.title != null)
            return false;
        if (startTime != null ? !startTime.equals(item.startTime) : item.startTime != null)
            return false;
        if (finishTime != null ? !finishTime.equals(item.finishTime) : item.finishTime != null)
            return false;
        if (date != null ? !date.equals(item.date) : item.date != null) return false;
        return !(time != null ? !time.equals(item.time) : item.time != null);

    }

    @Override
    public int hashCode() {
        int result = day != null ? day.hashCode() : 0;
        result = 31 * result + (title != null ? title.hashCode() : 0);
        result = 31 * result + (startTime != null ? startTime.hashCode() : 0);
        result = 31 * result + (finishTime != null ? finishTime.hashCode() : 0);
        result = 31 * result + (date != null ? date.hashCode() : 0);
        result = 31 * result + (time != null ? time.hashCode() : 0);
        return result;
    }

    /**
     * @return List of elements prepared for tests
     */
    public static ArrayList<Item> getTestingList() {
        ArrayList<Item> items = new ArrayList<>();
        items.add(new Item("Ven", "08h00", "09h00", "Context Aware IoT Sytem", "TODAY", "08:00 AM", "GROUP", "Damaris, Dorian, Juan, Julien", "RESUME", "Resume", "UF", "Innovative Project", "Duration", "60 minutes", "Download group report"));
        items.add(new Item("Ven", "09h00", "10h00", "Waky Baby", "TODAY", "09:00 AM", "GROUP", "Margot, Loran, Jules, Axelle, Damien", "RESUME", "Resume", "UF", "Innovative Project", "Duration", "60 minutes", "Download group report"));
        items.add(new Item("Ven", "10h00", "11h00", "Social network for pollution", "TODAY", "10:00 AM", "GROUP", "Tehema, Ting, Bravo, David, Amine", "RESUME", "Resume", "UF", "Innovative Project", "Duration", "60 minutes", "Download group report"));
        items.add(new Item("Ven", "11h00", "12h00", "Autonomous connected RF instrumentation for Satellites", "TODAY", "11:00 AM", "GROUP", "Damaris, Dorian, Juan, Julien", "RESUME", "Resume", "UF", "Innovative Project", "Duration", "60 minutes", "Download group report"));
        items.add(new Item("Ven", "14h00", "15h00", "UltraOrdinaire", "TODAY", "14:00 AM", "GROUP", "Agathe, Sophie, Guangjie, Jonathan, Elie", "RESUME", "Resume", "UF", "Innovative Project", "Duration", "60 minutes", "Download group report"));
        return items;

    }

}
