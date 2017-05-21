package com.example.piyush.finalyearproject;

import android.annotation.TargetApi;
import android.content.Intent;
import android.content.res.ColorStateList;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static com.example.piyush.finalyearproject.R.color.*;

public class SelectCategory extends AppCompatActivity {

    SessionManager session;

    JSONParser jParser = new JSONParser();
    JSONArray cat_array=null;

    private static final String TAG_CAT_ID="cat_id";
    private static final String TAG_CAT_NAME="cat_name";
    private static final String TAG_CATEGORY="category";
    private static final String TAG_SUCCESS="success";

    String urls = null,url2=null;
    int success=0;

    RadioGroup radioGroup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_category);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        urls=getString(R.string.baseURL1)+"get_cat";
        url2=getString(R.string.baseURL1)+"add_user_cat";

        session = new SessionManager(getApplicationContext());
        Log.d("Big Category Selection",session.getBIG_CAT());
        radioGroup = (RadioGroup)findViewById(R.id.radioGroupCat);

        new GetCat().execute();


    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if(session.IsLoggedIn())
            getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();

        switch (id){
            case R.id.menuLogout:
                Log.d("Logging Out", "");
                session.logoutUser();
                break;
        }

        return super.onOptionsItemSelected(item);
    }

    @TargetApi(Build.VERSION_CODES.LOLLIPOP)
    protected void show_category(){
        try {
            RadioGroup ll = new RadioGroup(this);
            ll.setOrientation(LinearLayout.VERTICAL);
            for (int i = 0; i < cat_array.length(); i++) {
                JSONObject jsonobjstatus = cat_array.getJSONObject(i);
                String str_uname = jsonobjstatus.getString(TAG_CAT_ID);
                String str_cat_name = jsonobjstatus.getString(TAG_CAT_NAME);
                Log.d("fsdvdv",str_cat_name);
                RadioButton rdbtn = new RadioButton(this);
                rdbtn.setId(Integer.parseInt(str_uname));
                rdbtn.setText(str_cat_name);
                rdbtn.setTextColor(getResources().getColor(txtColor));
                rdbtn.setButtonTintList(ColorStateList.valueOf(getResources().getColor(txtColor)));
                ll.addView(rdbtn);
            }
            ((ViewGroup) findViewById(R.id.radioGroupCat)).addView(ll);
            ll.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(RadioGroup group, int checkedId) {
                    Log.d("some thing", String.valueOf(checkedId));
                    if(session.IsLoggedIn())
                    {
                        new AddUserHistory().execute();
                    }
                    session.setSMALL_CAT(String.valueOf(checkedId));
                    Intent intent = new Intent(getApplicationContext(),SelectLocateCity.class);
                    startActivity(intent);
                }
            });
        }
        catch (Exception e){
            Log.i("Exception", String.valueOf(e));
        }
    }


    class AddUserHistory extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.d("select Category","entered");
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            String ratign_urls = url2;
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("cat_id",session.getSmallCat()));
            nameValuePair.add(new BasicNameValuePair("user_id", String.valueOf(session.getUserId())));
            JSONObject json = jParser.makeHttpRequest(ratign_urls, "POST",nameValuePair);
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {

        }
    }



    class GetCat extends AsyncTask<String, String, String> {
        @Override
        protected void onPreExecute() {
            Log.i("preexecution", "entered");
        }
        @Override
        protected String doInBackground(String... args) {
            Log.i("background execution", "entered");
            List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>();
            nameValuePair.add(new BasicNameValuePair("parent_cat", session.getBIG_CAT()));
            JSONObject json = jParser.makeHttpRequest(urls, "POST",nameValuePair);
            try {
                success = json.getInt(TAG_SUCCESS);
                cat_array=json.getJSONArray(TAG_CATEGORY);
            }
            catch(Exception e){
                Log.i("Exception", String.valueOf(e));
            }
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            Log.i("prexecution execution", "entered");
            if(success==1)
                show_category();
            else
            {
                Intent redirect_i=new Intent(getApplicationContext(),MainActivity.class);
                startActivity(redirect_i);
            }
        }
    }

}
