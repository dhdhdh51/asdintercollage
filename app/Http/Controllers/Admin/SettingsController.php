<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show the settings panel.
     */
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general site settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:100',
            'site_tagline'     => 'nullable|string|max:200',
            'contact_email'    => 'nullable|email',
            'contact_phone'    => 'nullable|string|max:20',
            'contact_address'  => 'nullable|string',
            'footer_text'      => 'nullable|string|max:500',
            'academic_year'    => 'nullable|string|max:20',
        ]);

        $fields = [
            'site_name', 'site_tagline', 'contact_email', 'contact_phone',
            'contact_address', 'footer_text', 'academic_year'
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->$field);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpg,jpeg,png,svg|max:2048']);
            $path = $request->file('logo')->store('public/uploads/logo');
            Setting::set('logo', Storage::url($path));
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'file|mimes:ico,png|max:512']);
            $path = $request->file('favicon')->store('public/uploads/favicon');
            Setting::set('favicon', Storage::url($path));
        }

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update SMTP email settings.
     */
    public function updateSmtp(Request $request)
    {
        $request->validate([
            'mail_host'     => 'required|string',
            'mail_port'     => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_from'     => 'required|email',
            'mail_name'     => 'required|string',
        ]);

        $smtpFields = [
            'mail_host', 'mail_port', 'mail_username',
            'mail_password', 'mail_from', 'mail_name', 'mail_encryption'
        ];

        foreach ($smtpFields as $field) {
            Setting::set($field, $request->$field);
        }

        // Update config at runtime
        config(['mail.mailers.smtp.host' => $request->mail_host]);
        config(['mail.mailers.smtp.port' => $request->mail_port]);
        config(['mail.mailers.smtp.username' => $request->mail_username]);
        config(['mail.mailers.smtp.password' => $request->mail_password]);

        return redirect()->back()->with('success', 'SMTP settings updated successfully.');
    }

    /**
     * Update SEO settings.
     */
    public function updateSeo(Request $request)
    {
        $seoFields = [
            'meta_title', 'meta_description', 'meta_keywords',
            'og_title', 'og_description', 'og_image',
            'google_analytics', 'google_site_verification',
            'schema_name', 'schema_description', 'schema_url',
            'schema_phone', 'schema_address',
        ];

        foreach ($seoFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        return redirect()->back()->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Update PayU payment gateway settings.
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'payu_merchant_key'  => 'required|string',
            'payu_merchant_salt' => 'required|string',
        ]);

        Setting::set('payu_merchant_key', $request->payu_merchant_key);
        Setting::set('payu_merchant_salt', $request->payu_merchant_salt);
        Setting::set('payu_mode', $request->payu_mode ?? 'test');

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }

    /**
     * Toggle feature on/off.
     */
    public function toggleFeature(Request $request)
    {
        $request->validate(['feature' => 'required|string', 'value' => 'required|in:0,1']);
        Setting::set('feature_' . $request->feature, $request->value);
        return response()->json(['success' => true]);
    }
}
