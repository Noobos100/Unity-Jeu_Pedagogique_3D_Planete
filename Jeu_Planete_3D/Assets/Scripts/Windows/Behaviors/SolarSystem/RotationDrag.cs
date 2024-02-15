using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class RotationDrag : MonoBehaviour
{

    [SerializeField] private RotationCycle rotationCycle;
    public float speedRotation = 11f;
    private bool active = true;
    [SerializeField] private Renderer render;
    [SerializeField] private MaterialPropertyBlock mpb;
   
    private void Start()
    {
        if (rotationCycle == null)
        {
            rotationCycle = gameObject.GetComponent<RotationCycle>();
            if (rotationCycle == null)
            {
                active = false;
                enabled = false;
            }
        }
    }
    
    private void OnEnable()
    {
        render = GetComponent<Renderer> ();
        mpb = new MaterialPropertyBlock ();
        if (rotationCycle == null)
        {
            active = false;
            enabled = false;
            return;
        }
        active = true;
        StartCoroutine(DragAndRotate());
    }

    private void OnDisable()
    {
        active = false;
        StopCoroutine(DragAndRotate());
    }

   
    private IEnumerator DragAndRotate()
    {
        while (active)
        {
            // Find angle to add to the rotation
            float xRotate = Input.GetAxis("Mouse X") * speedRotation * (-1);
            xRotate %= 360f;
            
            // Calculate newProgress
            float newProgress = rotationCycle.rotateProgress + xRotate / 360f;
            
            if (newProgress < 0)
            {
                newProgress += 1;
            }
            else if (newProgress >= 1)
            {
                newProgress %= 1f;
            }
            rotationCycle.rotateProgress = newProgress;
            
            // Change object's rotation
            render.GetPropertyBlock (mpb);
            mpb.SetFloat("_RotationPeriod", speedRotation);
            mpb.SetFloat ("_RotationProgress", rotationCycle.rotateProgress);
            GetComponent<Renderer>().SetPropertyBlock (mpb);
            
            yield return null;
        }
        yield return null;
    }
    
    
}
