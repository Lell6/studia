package com.example.moneytracker;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.util.AttributeSet;
import android.view.View;

import java.util.List;

public class BarChartView extends View {
    private Paint barPaint1;
    private Paint barPaint2;
    private Paint textPaint;
    private Paint valuePaint;

    private List<String> dates;
    private List<Double> category1Sums;
    private List<Double> category2Sums;
    private String category1Name;
    private String category2Name;

    public BarChartView(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    private void init() {
        barPaint1 = new Paint();
        barPaint1.setColor(Color.BLUE);
        barPaint1.setStyle(Paint.Style.FILL);

        barPaint2 = new Paint();
        barPaint2.setColor(Color.GREEN);
        barPaint2.setStyle(Paint.Style.FILL);

        textPaint = new Paint();
        textPaint.setColor(Color.BLACK);
        textPaint.setTextSize(32);

        valuePaint = new Paint();
        valuePaint.setColor(Color.RED);
        valuePaint.setTextSize(28);
        valuePaint.setTextAlign(Paint.Align.CENTER);
    }

    public void setData(List<String> dates, List<Double> category1Sums, List<Double> category2Sums, String category1, String category2) {
        this.dates = dates;
        this.category1Sums = category1Sums;
        this.category2Sums = category2Sums;
        this.category1Name = category1;
        this.category2Name = category2;
        invalidate();
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);

        if (dates == null || dates.isEmpty()) return;

        int width = getWidth();
        int height = getHeight();

        int barWidth = 75;
        int spacing = 50;
        int groupSpacing = 100;
        int startX = 20;
        int maxBarHeight = height - 200;

        double maxValue = 0;
        for (double val : category1Sums) maxValue = Math.max(maxValue, val);
        for (double val : category2Sums) maxValue = Math.max(maxValue, val);

        for (int i = 0; i < dates.size(); i++) {
            String date = dates.get(i);
            double value1 = category1Sums.get(i);
            double value2 = category2Sums.get(i);

            int bar1Height = (int) ((value1 / maxValue) * maxBarHeight);
            int bar2Height = (int) ((value2 / maxValue) * maxBarHeight);

            int bar1XStart = startX;
            int bar1XEnd = startX + barWidth;
            canvas.drawRect(bar1XStart, height - bar1Height - 100, bar1XEnd, height - 100, barPaint1);
            canvas.drawText(category1Name, bar1XStart + barWidth / 2, height - 50, textPaint);
            canvas.drawText(String.valueOf(value1), bar1XStart + barWidth / 2, height - bar1Height - 120, valuePaint);

            int bar2XStart = startX + barWidth + spacing;
            int bar2XEnd = bar2XStart + barWidth;
            canvas.drawRect(bar2XStart, height - bar2Height - 100, bar2XEnd, height - 100, barPaint2);
            canvas.drawText(category2Name, bar2XStart + barWidth / 2, height - 50, textPaint);
            canvas.drawText(String.valueOf(value2), bar2XStart + barWidth / 2, height - bar2Height - 120, valuePaint);

            canvas.drawText(date, startX + barWidth, height - 10, textPaint);

            startX += 2 * barWidth + groupSpacing;
        }
    }
}