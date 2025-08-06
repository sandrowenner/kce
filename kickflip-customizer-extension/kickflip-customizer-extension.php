<?php
/**
 * Plugin Name:       Kickflip Customizer Extension
 * Description:       Adiciona a funcionalidade do customizador Kickflip aos Produtos Variáveis do WooCommerce, incluindo acordeão de personalização no mini-cart do tema Ohio.
 * Version:           1.3.6
 * Author:            Sandro, Erik Lima & Gemini
 * License:           GPL-2.0-or-later
 * Text Domain:       kickflip-customizer-extension
 */

if (!defined('ABSPATH')) exit;

// Admin
require_once __DIR__ . '/includes/admin/compatibility.php';
require_once __DIR__ . '/includes/admin/product-type.php';
require_once __DIR__ . '/includes/admin/scripts.php';

// Frontend
require_once __DIR__ . '/includes/frontend/render-custom-button.php';
require_once __DIR__ . '/includes/frontend/assets.php';
require_once __DIR__ . '/includes/frontend/cart-hooks.php';
require_once __DIR__ . '/includes/frontend/accordion.php';
require_once __DIR__ . '/includes/frontend/mini-cart.php';

// AJAX
require_once __DIR__ . '/includes/ajax/add-to-cart.php';
require_once __DIR__ . '/includes/ajax/get-cart.php';

// Utils
require_once __DIR__ . '/includes/utils.php';