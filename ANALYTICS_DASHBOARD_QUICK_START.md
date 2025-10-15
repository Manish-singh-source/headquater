# Analytics Dashboard - Quick Start Guide

## 🚀 Quick Access

**URL**: `http://your-domain/analytics-dashboard`  
**Menu**: Sidebar → "Analytics Dashboard"

## 📊 Dashboard Sections Overview

### 1. Sales Analytics (Green Header)
- **Total Sales Till Date**: Current year cumulative sales
- **4-Month Trend**: Line chart with brand comparison
- **Brand Table**: Detailed sales breakdown

### 2. Purchase Analytics (Blue Header)
- **Total Purchases Till Date**: Current year cumulative purchases
- **4-Month Trend**: Line chart with brand comparison
- **Brand Table**: Detailed purchase breakdown

### 3. Order Status (Yellow Header)
- **Total Orders**: All orders count
- **Open Orders**: Pending orders (yellow badge)
- **Processed Orders**: Completed orders (green badge)
- **Brand Table**: Order status by brand with % processed

### 4. Dispatch Status (Gray Header)
- **LR Pending**: Red card
- **Appointments Pending**: Yellow card
- **Appt. Received (GRN Pending)**: Blue card
- **Pie Chart**: Visual breakdown

### 5. Delivery Confirmation (Green Header)
- **POD Received**: Green card
- **POD Not Received**: Red card
- **Donut Chart**: Visual breakdown

### 6. GRN Status (Dark Header)
- **GRN Done**: Green card
- **GRN Not Done**: Red card
- **Horizontal Bar Chart**: Visual comparison

### 7. Payment Status (Blue Header)
- **Total Outstanding**: Red card (all unpaid)
- **Monthly Received**: Green card (current month)
- **Overdue**: Yellow card (past due date)
- **4-Month Trend**: Payment received line chart

### 8. Warehouse Inventory (Teal Header)
- **Total Units**: Total inventory count
- **Total Value**: Total inventory value in ₹
- **Brand Table**: Units and value by brand

## 🔍 Using Filters

### Date Range Filter
```
Default: Current month (start to end)
Usage: Select start date → Select end date → Click "Apply"
```

### Brand Filter
```
Default: All brands
Usage: Click dropdown → Select brands (Ctrl+Click for multiple) → Click "Apply"
```

### Reset Filters
```
Click "Reset" button to return to defaults
```

## 🎨 Color Legend

| Color | Meaning | Usage |
|-------|---------|-------|
| 🟢 Green | Positive/Completed | Sales, POD Received, GRN Done, Payments Received |
| 🔴 Red | Critical/Pending | LR Pending, POD Not Received, Outstanding |
| 🟡 Yellow | Warning/In Progress | Appointments Pending, Overdue Payments |
| 🔵 Blue | Informational | Purchases, Total Orders, Neutral metrics |
| 🟦 Teal | Inventory | Warehouse data |

## 📈 Chart Interactions

### Hover Tooltips
- Hover over any chart element to see exact values
- Line charts show value with currency formatting
- Pie/Donut charts show count and percentage

### Legend
- Click legend items to show/hide data series (line charts)
- All charts have legends at the bottom

## 💡 Quick Tips

1. **Best Practice**: Filter by specific brands for focused analysis
2. **Performance**: Shorter date ranges load faster
3. **Comparison**: Use line charts to compare brand performance
4. **Export**: Take screenshots for reports (export feature coming soon)
5. **Mobile**: Dashboard is fully responsive - works on tablets and phones

## 🔧 Technical Details

### Data Refresh
- Data updates on page load
- Click "Apply" after changing filters to refresh
- No auto-refresh (manual refresh required)

### Date Ranges
- **Till Date**: From January 1st of current year
- **Trends**: Last 3 months + current month (4 data points)
- **Current Month**: From 1st to last day of current month

### Brand Segmentation
- All metrics support brand filtering
- Brand data comes from `products.brand` field
- Empty/null brands are excluded

## 📱 Responsive Breakpoints

- **Desktop** (>1200px): Full layout, side-by-side sections
- **Tablet** (768px-1199px): Stacked layout, 2 columns
- **Mobile** (<768px): Single column, scrollable tables

## 🐛 Common Issues & Solutions

### Issue: No data showing
**Solution**: 
- Check if products have brand field populated
- Verify date range includes data
- Try "Reset" button

### Issue: Charts not loading
**Solution**:
- Check internet connection (Chart.js loads from CDN)
- Clear browser cache
- Try different browser

### Issue: Slow loading
**Solution**:
- Use shorter date ranges
- Filter by specific brands
- Contact admin if persistent

## 📞 Support

For issues or questions:
1. Check this guide first
2. Review full documentation (ANALYTICS_DASHBOARD_README.md)
3. Contact development team
4. Submit ticket through project management system

## 🎯 Key Metrics Explained

### Sales
- **Total Sales**: Sum of all invoice amounts
- **Trend**: Monthly sales aggregated by brand

### Purchases
- **Total Purchases**: Sum of all purchase order amounts
- **Trend**: Monthly purchases aggregated by brand

### Orders
- **Open**: Status = 'pending'
- **Processed**: Status = 'completed' or 'delivered'
- **% Processed**: (Processed / Total) × 100

### Dispatch
- **LR Pending**: Invoices without appointments
- **Appointments Pending**: Invoices without appointment dates
- **GRN Pending**: Appointments without GRN files

### Delivery
- **POD Received**: Appointments with POD file uploaded
- **POD Not Received**: Appointments without POD file

### GRN
- **GRN Done**: Appointments with GRN file uploaded
- **GRN Not Done**: Appointments without GRN file

### Payments
- **Outstanding**: Invoices without paid status
- **Monthly Received**: Payments marked as 'paid' in current month
- **Overdue**: Invoices older than 30 days without payment

### Warehouse
- **Units**: Sum of available_quantity from warehouse_stocks
- **Value**: Units × net_landing_rate per product

## 🔐 Access Control

- **Required**: User must be logged in
- **Permissions**: All authenticated users can access
- **Data Visibility**: Shows data based on user's access level

## 📊 Sample Use Cases

### Use Case 1: Monthly Performance Review
1. Keep default date range (current month)
2. Review all sections for overview
3. Compare with previous months using trend charts

### Use Case 2: Brand-Specific Analysis
1. Select specific brand(s) from filter
2. Click "Apply"
3. Review all metrics for selected brand(s)

### Use Case 3: Logistics Tracking
1. Focus on Dispatch, Delivery, and GRN sections
2. Identify pending items (red/yellow cards)
3. Take action on critical items

### Use Case 4: Financial Overview
1. Review Sales and Payment sections
2. Check outstanding amounts
3. Monitor payment trends

### Use Case 5: Inventory Management
1. Go to Warehouse section
2. Review inventory by brand
3. Identify low-stock brands

## 🎓 Training Resources

- **Video Tutorial**: Coming soon
- **Full Documentation**: ANALYTICS_DASHBOARD_README.md
- **User Manual**: This guide
- **FAQ**: Contact support for common questions

---

**Quick Reference Version**: 1.0.0  
**Last Updated**: 2025-10-14  
**For**: End Users and Administrators

