jQuery(document).ready(function($) {
    // Test connection
    $('#test-connection').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var originalText = button.text();
        
        button.prop('disabled', true).text('Testing...');
        $('#iptv-messages').html('');
        
        $.ajax({
            url: iptvIntegration.ajax_url,
            type: 'POST',
            data: {
                action: 'iptv_test_connection',
                nonce: iptvIntegration.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#iptv-messages').html('<div class="notice notice-success"><p>' + response.data + '</p></div>');
                } else {
                    $('#iptv-messages').html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                }
            },
            error: function() {
                $('#iptv-messages').html('<div class="notice notice-error"><p>An error occurred. Please try again.</p></div>');
            },
            complete: function() {
                button.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Sync products
    $('#sync-products').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var originalText = button.text();
        
        if (!confirm('This will create or update WordPress pages for all products. Continue?')) {
            return;
        }
        
        button.prop('disabled', true).text('Syncing...');
        $('#iptv-messages').html('<div class="notice notice-info"><p>Syncing products, please wait...</p></div>');
        
        $.ajax({
            url: iptvIntegration.ajax_url,
            type: 'POST',
            data: {
                action: 'iptv_sync_products',
                nonce: iptvIntegration.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#iptv-messages').html('<div class="notice notice-success"><p>' + response.data + '</p></div>');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    $('#iptv-messages').html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                }
            },
            error: function() {
                $('#iptv-messages').html('<div class="notice notice-error"><p>An error occurred. Please try again.</p></div>');
            },
            complete: function() {
                button.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Clear data
    $('#clear-data').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var originalText = button.text();
        
        if (!confirm('⚠️ WARNING: This will permanently delete all created product pages and clear all plugin data. This action cannot be undone!\n\nAre you sure you want to continue?')) {
            return;
        }
        
        if (!confirm('This is your last chance. Are you absolutely sure you want to delete all pages and clear all data?')) {
            return;
        }
        
        button.prop('disabled', true).text('Clearing...');
        $('#iptv-messages').html('<div class="notice notice-info"><p>Clearing data and deleting pages, please wait...</p></div>');
        
        $.ajax({
            url: iptvIntegration.ajax_url,
            type: 'POST',
            data: {
                action: 'iptv_clear_data',
                nonce: iptvIntegration.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#iptv-messages').html('<div class="notice notice-success"><p>' + response.data + '</p></div>');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    $('#iptv-messages').html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                }
            },
            error: function() {
                $('#iptv-messages').html('<div class="notice notice-error"><p>An error occurred. Please try again.</p></div>');
            },
            complete: function() {
                button.prop('disabled', false).text(originalText);
            }
        });
    });
});

