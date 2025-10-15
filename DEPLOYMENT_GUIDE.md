# Analytics Dashboard - Deployment Guide

## Overview

This guide provides step-by-step instructions for deploying the Multi-Brand Analytics Dashboard to production.

## Pre-Deployment Checklist

- [ ] All code changes committed to version control
- [ ] Testing completed (see TESTING_CHECKLIST.md)
- [ ] Database backup created
- [ ] Staging environment tested
- [ ] Rollback plan prepared
- [ ] Stakeholders notified of deployment
- [ ] Maintenance window scheduled (if needed)

## System Requirements

### Server Requirements
- **PHP**: 8.2 or higher
- **Laravel**: 12.x
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: Minimum 512MB, Recommended 1GB+
- **Disk Space**: 100MB for application files

### Client Requirements
- **Modern Browser**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **JavaScript**: Enabled
- **Internet Connection**: Required for Chart.js CDN

## Deployment Steps

### Step 1: Backup Current System

```bash
# 1. Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Backup application files
cd /path/to/application
tar -czf backup_files_$(date +%Y%m%d_%H%M%S).tar.gz .

# 3. Verify backups
ls -lh backup_*
```

### Step 2: Pull Latest Code

```bash
# Navigate to application directory
cd c:\xampp\htdocs\headquater

# Pull latest changes from repository
git pull origin main

# Or if deploying from zip file
# Extract files to application directory
```

### Step 3: Install/Update Dependencies

```bash
# Update Composer dependencies
composer install --optimize-autoloader --no-dev

# Clear and rebuild cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Database Optimization (Optional but Recommended)

```bash
# Run database optimization script
mysql -u username -p database_name < database_optimization.sql

# Or run via Laravel
php artisan db:seed --class=DatabaseOptimizationSeeder
```

### Step 5: Verify File Permissions

```bash
# Set correct permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (XAMPP)
# Ensure XAMPP user has read/write access to storage and bootstrap/cache
```

### Step 6: Test in Staging/Development

```bash
# Start development server (for testing)
php artisan serve

# Access dashboard
# http://localhost:8000/analytics-dashboard

# Run automated tests
php artisan test --filter=DashboardTest
```

### Step 7: Deploy to Production

```bash
# Put application in maintenance mode
php artisan down --message="Deploying Analytics Dashboard" --retry=60

# Run any pending migrations (if any)
php artisan migrate --force

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring application back online
php artisan up
```

### Step 8: Post-Deployment Verification

```bash
# Check application status
php artisan about

# Verify routes are registered
php artisan route:list | grep analytics

# Check for errors in logs
tail -f storage/logs/laravel.log
```

## Manual Verification Steps

### 1. Access Dashboard
- [ ] Navigate to: `http://your-domain/analytics-dashboard`
- [ ] Verify page loads without errors
- [ ] Check browser console for JavaScript errors

### 2. Test Filters
- [ ] Apply date range filter
- [ ] Apply brand filter
- [ ] Click Reset button
- [ ] Verify data updates correctly

### 3. Verify Charts
- [ ] All charts render properly
- [ ] Hover tooltips work
- [ ] Charts are responsive

### 4. Check Data Accuracy
- [ ] Compare KPI values with database queries
- [ ] Verify brand-wise breakdowns
- [ ] Check trend data

### 5. Test Responsive Design
- [ ] Test on desktop browser
- [ ] Test on tablet (or resize browser)
- [ ] Test on mobile device

### 6. Performance Check
- [ ] Page load time < 3 seconds
- [ ] Filter application < 2 seconds
- [ ] No lag when scrolling

## Configuration

### Environment Variables

No additional environment variables are required. The dashboard uses existing Laravel configuration.

### CDN Configuration

The dashboard uses Chart.js from CDN:
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

**Alternative**: If CDN is not accessible, download Chart.js and host locally:
1. Download from: https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js
2. Save to: `public/assets/js/chart.min.js`
3. Update script tag in `analytics-dashboard.blade.php`

## Rollback Procedure

If issues occur after deployment:

### Quick Rollback

```bash
# 1. Put application in maintenance mode
php artisan down

# 2. Restore previous code version
git reset --hard HEAD~1
# Or restore from backup
tar -xzf backup_files_YYYYMMDD_HHMMSS.tar.gz

# 3. Restore database (if needed)
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql

# 4. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Bring application back online
php artisan up
```

### Partial Rollback (Remove Dashboard Only)

```bash
# 1. Remove route
# Edit routes/web.php and comment out analytics dashboard route

# 2. Remove sidebar link
# Edit resources/views/layouts/master.blade.php and comment out analytics link

# 3. Clear caches
php artisan route:clear
php artisan view:clear

# Dashboard will be inaccessible but rest of application continues working
```

## Monitoring

### Application Logs

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Check for errors
grep "ERROR" storage/logs/laravel.log

# Check for dashboard-specific errors
grep "DashboardController" storage/logs/laravel.log
```

### Web Server Logs

```bash
# Apache access log
tail -f /var/log/apache2/access.log | grep analytics-dashboard

# Apache error log
tail -f /var/log/apache2/error.log

# Nginx access log
tail -f /var/log/nginx/access.log | grep analytics-dashboard

# Nginx error log
tail -f /var/log/nginx/error.log
```

### Database Performance

```sql
-- Check slow queries
SELECT * FROM mysql.slow_log 
WHERE sql_text LIKE '%products%' 
ORDER BY query_time DESC 
LIMIT 10;

-- Check query execution time
EXPLAIN SELECT /* your query here */;
```

## Performance Optimization

### 1. Enable Caching

```php
// In DashboardController, add caching
$salesData = Cache::remember('dashboard.sales.' . md5(serialize($filters)), 3600, function() {
    return $this->getSalesData(...);
});
```

### 2. Database Optimization

```bash
# Run optimization script
mysql -u username -p database_name < database_optimization.sql

# Analyze tables
mysql -u username -p -e "ANALYZE TABLE products, invoices, sales_orders, purchase_orders, appointments, payments, warehouse_stocks;"
```

### 3. Enable OPcache

```ini
; In php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 4. Enable Gzip Compression

```apache
# In .htaccess (Apache)
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

## Troubleshooting

### Issue: Dashboard not accessible

**Solution**:
```bash
# Check route is registered
php artisan route:list | grep analytics

# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Issue: Charts not displaying

**Solution**:
1. Check browser console for errors
2. Verify Chart.js CDN is accessible
3. Check if data is being passed to view
4. Clear browser cache

### Issue: Slow performance

**Solution**:
1. Run database optimization script
2. Add indexes to database tables
3. Implement caching
4. Reduce date range in filters

### Issue: Incorrect data

**Solution**:
1. Verify database data is correct
2. Check filter parameters
3. Review controller logic
4. Check for timezone issues

## Security Considerations

### 1. Authentication
- Dashboard requires authentication
- Uses Laravel's built-in auth middleware
- No additional configuration needed

### 2. Authorization
- Consider adding role-based access control
- Restrict access to specific user roles if needed

### 3. Data Protection
- No sensitive data in URLs
- CSRF protection enabled
- SQL injection protection via Eloquent ORM

### 4. HTTPS
- Ensure application is served over HTTPS in production
- Update Chart.js CDN URL to use HTTPS

## Support

### Documentation
- **Full Documentation**: ANALYTICS_DASHBOARD_README.md
- **Quick Start Guide**: ANALYTICS_DASHBOARD_QUICK_START.md
- **Testing Checklist**: TESTING_CHECKLIST.md

### Contact
- **Technical Issues**: Contact development team
- **Feature Requests**: Submit through project management system
- **Urgent Issues**: Contact system administrator

## Deployment Checklist

- [ ] Backup completed
- [ ] Code deployed
- [ ] Dependencies updated
- [ ] Database optimized
- [ ] Caches cleared
- [ ] Production tested
- [ ] Performance verified
- [ ] Logs monitored
- [ ] Stakeholders notified
- [ ] Documentation updated

## Sign-off

**Deployed By**: ___________________  
**Date**: ___________________  
**Version**: 1.0.0  
**Environment**: ☐ Development ☐ Staging ☐ Production  
**Status**: ☐ Success ☐ Failed ☐ Rolled Back

---

**Next Steps After Deployment**:
1. Monitor logs for 24 hours
2. Gather user feedback
3. Address any issues promptly
4. Plan for future enhancements

