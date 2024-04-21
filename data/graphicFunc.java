public class graphicFunc {

    int upX;
    int up;

    int downX;

    int down;

    public double calculateY(double x){
        double buf=(-up-x*upX)/(down+downX*x);
        return buf;
    }

    public double calculateX(double y){
        double buf=(-up-y*down)/(upX+downX*y);
        return buf;
    }

    public graphicFunc(vektorObj func)
    {
        up=func.vect[0];
        upX=func.vect[1];
        down=func.vect[2];
        downX=func.vect[3];
    }

}
