package com.example.studentmonitor;

import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    Background background = null;

    // Commands
    public static final int cmd_Idle = 0;
    public static final int cmd_Initialize = 1;
    public static final int cmd_PayingAttention = 2;
    public static final int cmd_Distracted = 3;

    Context context;
    private static final String communicationUrl = "https://www.student-monitor.tk/sm/communication.php";
    private static RequestQueue requestQueue;
    private int command = cmd_Idle;
    private boolean running = false;

    private void backgroundToggle(boolean enable)
    {
        // Start background service
        if (enable)
        {
            background.startAlarm();
        }
        // Stop background service
        else
        {
            background.stopAlarm();
        }
    }

    private void updateUI()
    {
        if (running)
        {
            Button button = (Button) findViewById(R.id.connect_button);
            button.setText("Disconnect");

            EditText editTextStudent = (EditText) findViewById(R.id.editTextStudent);
            editTextStudent.setEnabled(false);

            EditText editTextPassword = (EditText) findViewById(R.id.editTextPassword);
            editTextPassword.setEnabled(false);

            EditText editTextClassId = (EditText) findViewById(R.id.editTextClassId);
            editTextClassId.setEnabled(false);
        }
        else
        {
            Button button = (Button) findViewById(R.id.connect_button);
            button.setText("Connect to class");

            EditText editTextStudent = (EditText) findViewById(R.id.editTextStudent);
            editTextStudent.setEnabled(true);

            EditText editTextPassword = (EditText) findViewById(R.id.editTextPassword);
            editTextPassword.setEnabled(true);

            EditText editTextClassId = (EditText) findViewById(R.id.editTextClassId);
            editTextClassId.setEnabled(true);
        }
    }

    public boolean control(final int cmd)
    {
        if(command != cmd_Idle)
            return false;
        switch (cmd)
        {
            case cmd_Initialize:
                notifyStatus("good");
                break;
            case cmd_Distracted:
                notifyStatus("bad");
                break;
            case cmd_PayingAttention:
                notifyStatus("good");
                break;
        }
        command = cmd;
        return true;
    }

    private void fsm(boolean success)
    {
        switch(command)
        {
            case cmd_Initialize: {
                if (success)
                {
                    running = true;
                    updateUI();
                    backgroundToggle(true);
                }
                break;
            }
            case cmd_Distracted:
            case cmd_PayingAttention:{
                background.setAlarm();
            }

        }
        command = cmd_Idle;
    }

    private void notifyStatus(final String status)
    {
        StringRequest stringRequest = new StringRequest(Request.Method.POST, communicationUrl,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(context, "SM RESPONSE = " + response, Toast.LENGTH_SHORT).show();
                        Log.i("Sm-MainActivity", "Response is: "+ response);
                        fsm(true);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(context, "SM ERROR", Toast.LENGTH_SHORT).show();
                Log.i("Sm-MainActivity", "stringRequestError = " + error.toString());
                fsm(false);
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android", "true");
                params.put("status", status);
                return params;
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("Content-Type", "application/x-www-form-urlencoded");
                return params;
            }
        };
        requestQueue.add(stringRequest);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Initialize context
        context = this;

        // Initialize background
        background = new Background();
        background.setContext(this);

        // Initialize variables;
        running = false;

        // Initialize UI
        updateUI();

        // Initialize web connectivity
        requestQueue = Volley.newRequestQueue(context);

        // Initialize button
        Button button = (Button) findViewById(R.id.connect_button);
        button.setOnClickListener(new View.OnClickListener()
        {
            public void onClick(View v)
            {
                if (!running)
                    control(cmd_Initialize);
                else
                {
                    backgroundToggle(false);
                    running = false;
                    updateUI();
                }
            }
        });
    }
}
