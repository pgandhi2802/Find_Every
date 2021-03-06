package com.example.piyush.finalyearproject;

/**
 * Created by perfect-ub on 3/5/16.
 */
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.google.android.gms.gcm.GcmListenerService;

public class MyGcmListenerService extends GcmListenerService {

    private static final String TAG = "MyGcmListenerService";
    SessionManager session;


    @Override
    public void onMessageReceived(String from, Bundle data) {
        String shop_id = data.getString("shop_id");
        String message = data.getString("message");
        String category_name=data.getString("category_name");
        session = new SessionManager(getApplicationContext());
        Log.d(TAG, "Message: " + message);

        if (from.startsWith("/topics/")) {
        } else {
        }


        sendNotification(message,shop_id);
    }
    private void sendNotification(String message,String shop_id) {
        session.setShop_id(Integer.valueOf(shop_id));
        Intent intent = new Intent(this,Show_details.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0 /* Request code */, intent,
                PendingIntent.FLAG_ONE_SHOT);

        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
                .setSmallIcon(R.mipmap.ic_launcher)
                .setContentTitle("New Event")
                .setContentText(message)
                .setAutoCancel(true)
                .setSound(defaultSoundUri)
                .setContentIntent(pendingIntent);

        NotificationManager notificationManager =
                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);

        notificationManager.notify(0 /* ID of notification */, notificationBuilder.build());
    }
}