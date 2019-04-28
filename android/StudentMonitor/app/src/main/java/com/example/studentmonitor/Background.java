package com.example.studentmonitor;

import android.app.ActivityManager;
import android.app.AlarmManager;
import android.app.KeyguardManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.SystemClock;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import static android.content.Context.ACTIVITY_SERVICE;

public class Background extends BroadcastReceiver {

    private static Context context;
    private static final int ALARM_ID = 102; // This can be any random integer.
    PendingIntent pi = null;
    AlarmManager am= null;
    static boolean running = false;

    @Override
    public void onReceive(Context context, Intent intent)
    {
        Toast.makeText(context, "Alarm Triggered", Toast.LENGTH_SHORT).show();

        // Check for device Locked
        boolean deviceLocked = false;
        KeyguardManager myKM = (KeyguardManager) context.getSystemService(Context.KEYGUARD_SERVICE);
        if( myKM.inKeyguardRestrictedInputMode())
            deviceLocked = true;
        Log.i("SM-Background", "Locked: " + deviceLocked);

        // Check for app or not
        boolean usingOtherApp = false;
        ActivityManager am = (ActivityManager) context.getSystemService(ACTIVITY_SERVICE);
        ActivityManager.RunningTaskInfo foregroundTaskInfo = am.getRunningTasks(1).get(0);
        String foregroundTaskPackageName = foregroundTaskInfo .topActivity.getPackageName();
        PackageManager pm = context.getPackageManager();
        try
        {
            PackageInfo foregroundAppPackageInfo = pm.getPackageInfo(foregroundTaskPackageName, 0);
            String foregroundTaskAppName = foregroundAppPackageInfo.applicationInfo.loadLabel(pm).toString();
            usingOtherApp = !foregroundTaskAppName.equals("Student Monitor");
            Log.i("SM-Background", "Foreground app: " + foregroundTaskAppName );
            Log.i("SM-Background", "Using other app: " + usingOtherApp);
        }catch(Exception ignore) {}

        // TODO: Send online request

        // Re - set alarm
        setAlarm();
    }

    public void setContext(Context context)
    {
        this.context = context;
    }

    public void setAlarm()
    {
        if (!running)
            return;

        am = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);
        Intent intent = new Intent(context, Background.class);
        pi = PendingIntent.getBroadcast(context, ALARM_ID, intent, PendingIntent.FLAG_ONE_SHOT);

        CancelAlarm();
        am.setExact(AlarmManager.ELAPSED_REALTIME_WAKEUP,
                SystemClock.elapsedRealtime() + 3000, pi);
    }

    public void startAlarm()
    {
        running = true;
        setAlarm();
    }

    public void stopAlarm()
    {
        running = false;
    }

    public void CancelAlarm()
    {
        if (am!= null) {
            am.cancel(pi);
        }
    }
}
