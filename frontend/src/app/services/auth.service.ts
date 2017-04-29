import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Login } from './objects/login';

import { Observable } from 'rxjs/Observable';

import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';

@Injectable()
export class AuthService {

  private authUrl: string = 'http://vapeboyz.xyz/api/user/login';

  token: string;

  constructor(private http: Http) {
    let currentUser = JSON.parse(localStorage.getItem('currentUser'));
    this.token = currentUser && currentUser.jwt;
  }

  login(user: Login): Observable<boolean>{
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this.authUrl, user, options)
                    .map((res: Response) => {
                      let token = JSON.parse(res.json())['jwt'];
                      console.log('token: ', token);
                      if(token != null) {
                        this.token = token;
                        localStorage.setItem('currentUser',
                          JSON.stringify({ username: user.username, token: token }));
                        return true;
                      }else{
                        return false;
                      }
                    }
                  );
  }

  logout(): void{
    this.token = null;
    localStorage.removeItem('currentUser');
  }

  private handleError (error: Response | any): Observable<any> {
    // In a real world app, you might use a remote logging infrastructure
    let errMsg: string;
    if (error instanceof Response) {
      const body = error.json() || '';
      const err = body.error || JSON.stringify(body);
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error('error', errMsg);
    return Observable.throw(errMsg);
  }

}
