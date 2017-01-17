
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

public class maoyan {
	public static void main(String[] args) throws IOException{
		File file = new File("/var/www/html/filmdaily/maoyan_ri_piaofang_supplement/map.ttf");
		File filwout = new File("/var/www/html/filmdaily/maoyan_ri_piaofang_supplement/key.txt");
		filwout.setWritable(true,false);
		FileOutputStream ou = new FileOutputStream(filwout);
		FileInputStream in = new FileInputStream(file);
		byte[] bt = new byte[10000];
		int length = in.read(bt);
		byte[] kkx = new byte[4];
		for(int j=2; j<12; j++){
			for(int i=0; i<length; i=i+4){
				if(bt[i]==0&&bt[i+1]==0&&bt[i+2]==0&&bt[i+3]==j&&bt[i-8]==bt[i-4]
						&&bt[i-7]==bt[i-3]&&bt[i-6]==bt[i-2]&&bt[i-5]==bt[i-1]&&bt[i-4]==0&&bt[i-3]==0){
					kkx[0]= (byte) ((bt[i-2]&0xf0)>>4);
					kkx[1]= (byte) (bt[i-2]&0x0f);
					kkx[2]= (byte) ((bt[i-1]&0xf0)>>4);
					kkx[3]= (byte) (bt[i-1]&0x0f);
					break;
				}
			}
			for(int i=0;i<4;i++){
				switch(kkx[i]){
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					{
						kkx[i] += '0';
						break;
					}
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					{
						kkx[i] = (byte) (kkx[i]-10+'A');
						break;
					}
				}
			}
			ou.write(kkx);
			byte[] huiche = new byte[2];
			huiche[0] = '\r';
			huiche[1] = '\n';
			ou.write(huiche);
		}
		ou.close();
		in.close();

	}
}
