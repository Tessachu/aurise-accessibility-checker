/*!
    Name: admin-dashboard.js
    Author: AuRise Creative | https://aurisecreative.com
    Last Modified: 2023.02.07.15.44
*/
var $ = jQuery.noConflict(),
    auPluginAdminDashboard = {
        version: '2023.02.07.15.44',
        init: function() {
            //Plugin initialization
            //console.info('Initialising admin-dashboard.js. Last modified ' + auPluginAdminDashboard.version);
            auPluginAdminDashboard.tabs.init();
            auPluginAdminDashboard.forms.init();

            //Init complete, display admin UI
            auPluginAdminDashboard.initComplete();
        },
        tabs: {
            init: function() {
                //Hide all tabs
                $('.au-plugin section.tab').addClass('hide');

                //Add button listeners
                $('.au-plugin a.nav-tab').on('click', auPluginAdminDashboard.tabs.handler);
            },
            handler: function(event) {
                event.preventDefault();
                var tab = $(this).attr('href').replace('#', '');
                auPluginAdminDashboard.tabs.open(tab);
            },
            open: function(tab) {
                $('.au-plugin a.nav-tab, .au-plugin #tab-content section.tab').removeClass('nav-tab-active'); //Deactivate all of the tab buttons and tab contents
                $('.au-plugin #tab-content section.tab').addClass('hide'); //Hide all of the tab contents
                $('.au-plugin #' + tab).removeClass('hide').addClass('nav-tab-active'); //Show and activate the tab content
                $('.au-plugin #open-' + tab).addClass('nav-tab-active'); //Activate the tab button
            }
        },
        forms: {
            init: function() {
                auPluginAdminDashboard.forms.initSwitches();
            },
            initSwitches() {
                //Add checkbox listeners for switch toggles
                var $checkboxes = $('.au-plugin input[type="hidden"]+input[type="checkbox"]');
                if ($checkboxes.length) {
                    $('.au-plugin input[type="hidden"]+input[type="checkbox"]').on('click', auPluginAdminDashboard.forms.switchHandler);
                }
            },
            switchHandler: function(e) {
                //Updates the hidden field with the boolean value of the checkbox
                var $input = $(this),
                    checked = $input.is(':checked') || $input.prop('checked');
                if ($input.hasClass('reverse-checkbox')) {
                    //Reverse checkboxes show a positive association with the "false" value
                    $input.siblings('input[type="hidden"]').val(checked ? '0' : '1');
                } else {
                    $input.siblings('input[type="hidden"]').val(checked ? '1' : '0');
                }
            },
            getCheckbox: function(input) {
                //Returns a true/false boolean value based on whether the checkbox is checked
                var $input = $(input);
                return ($input.is(':checked') || $input.prop('checked'));
            },
            toggleCheckbox: function(input, passedValue) {
                //Changes a checkbox input to be checked or unchecked based on boolean parameter (or toggles if not included)
                //Only changes it visually - it does not change any data in any objects
                var $input = $(input);
                var value = passedValue;
                if (typeof(value) != 'boolean' || value === undefined) {
                    value = !auPluginAdminDashboard.forms.controlledFields.getCheckbox($input);
                }
                if (value) {
                    $input.attr('checked', 'checked');
                    $input.prop('checked', true);
                } else {
                    $input.removeAttr('checked');
                    $input.prop('checked', false);
                }
            }
        },
        initComplete: function() {
            //If there is a Hash in the URL, open that tab
            var current_tab = document.location.hash;
            if (current_tab && $(current_tab).length) {
                //open the current tab
                auPluginAdminDashboard.tabs.open(current_tab.replace('#', ''));
            } else {
                //open first tab
                auPluginAdminDashboard.tabs.open($('.au-plugin a.nav-tab').first().attr('href').replace('#', ''));
            }
            //init is completed. Hide loading spinner image and display the admin UI
            $('.au-plugin .loading-spinner').addClass('hide');
            $('.au-plugin .admin-ui').removeClass('hide');
            //console.info('Initialisation completed for admin-dashboard.js.');
        }
    };
$(document).ready(auPluginAdminDashboard.init);