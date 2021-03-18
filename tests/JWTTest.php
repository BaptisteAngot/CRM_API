<?php


namespace App\Tests;

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    private const FULL_TOKEN_USER = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5MzA0MTYsImV4cCI6MTYxNTkzNDAxNiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidXNlckB1c2VyLmNvbSJ9.hzUHwzqzXuff-4joae0PJKK8sXr7g2IwjQnA_XqzaYEpL2VrdlXlsgMr31yaSWDclZo4Mou3I_HM-c7EN4G3sNR01H8Ee3nD6uyKR08PAlOrX74JZLzl8ZuOJBrRokFJGYAIzhFp5Lxtk3C1lD9zI2WicuAlD7Kk1OAT3XPUR2tsPSMCYhQkcciPlvF6AKtPGJkvkTxNQc1NGFQN6YIzyCcFzNOP-HjALb-MC42f03pMJAcAay-jfvch0kE38ryENjDEG44VPPWIBGFFbA0cUqVOyZ-F6j_9tIPnAPBdVdWsCgm3gX7L6-C6TGNCF6cY-URqJCzaEByQMx6NG7eGsrrxWaX4O9_aB32n2Zlq-Y2TvgUwwHPSOatu_ItISU5zL73J3YNdQgY__2MaG8rrRyibd9apIUTacw0cCCzON3RwlOuVh2aHmwYiq16-rWdPSGeRykXpsyyq3fZNWhghIakEip1C0hGLpZxu9zaGWW7tYgrxgUaTFBcQnbl1m0PgxyozuODdfWNtQuLpA6_SWNTAFLdu02hagsQTcKRbndehEFL8v07z5MCunaIdqJUieAZ1Vl2VuD1sfA9krQ0GMwbuWlU1jUSJmru_M30CFjcVDu1qGrix7ChVkRD6u9Cwbe3ekl6eHdvI3h1Vh5ihbAQ4ZVUp08p5sTcZWYTe7Rw";
    private const FULL_TOKEN_ADMIN = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5MzE2ODQsImV4cCI6MTYxNTkzNTI4NCwicm9sZXMiOlsiUk9MRV9TVVBFUl9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImFkbWluQGFkbWluLmNvbSJ9.OCft6Hwv2SD1CErJR6qB4Tzlm6_GVh8f8FTPA413ZgdrzAZdpGiVmBw3PSvIATZgX9kcgOJRhVUgYjSpP6jAbAVlcxA2gvtYqVGm7PcDhcYDELFTG_nRsyN2-zWxqsQQAULY2pcpkAZQe7MbcsoEZvIWCNB94jj4CRzIY4uBMOlfiRjPcLADNJeOWzCv9bgWT9dEv5z9Jw4k57csJzm8UDhgcSsp5diw2kcB99pE_aqJruY_mOJWm4CKFAgjXXC6sLCmjQSCPzL0RxwdX5yo23M-2EYhaA2Fkwh-1jNvlShp8slULTAsP7C-cS1pIbOHeNNZa7gjffCduApjNpqHHr6-j7I79GLez0uZ5iAQ65o-ULxslRcleiyO5aeD4jU_gqNxUYGYjJ15sOJYCl_GJME__lYXUhktDWz5h7xmmYDfLWbiFlc-11C3ajS6jYAAdZLYeNzOSLcO4BPXf1jMF4BcAbi-Rp-jgNo9PrkH1EepCSP9ffUyxxlnwNF8doxQzbWTnxwYg_mSgbhVAiIlNAKzkTOkG6mHL5fZUIIoXMICFpuvDqOOLiWSqfmEc5NykG-FWdnW8UrFTdKGRJZeomd-aCAaMayitlP0stGVHuKJgRtek8UTPJsBJTdEVPV2iuO1sxjQAIvYnUfx1Y-aPGf6iNJC_2ULnGfKjhWju1I";
    private const OLD_TOKEN = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5Mjc4NjEsImV4cCI6MTYxNTkzMTQ2MSwicm9sZXMiOlsiUk9MRV9TVVBFUl9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImFkbWluQGFkbWluLmNvbSJ9.XiW0yECQ5JrG1CJ1u37-8XEPTGi6d4pHJBdfYVn2waIO_pDpGt5I8xB-vE04B3KK0sBl8Up_ZNr8eIGlEIXMXcDkV7bcGDwQSdMB_VGdstwgH5-LMCl3j0hNGoinFngZdvQos7c6BVkWSmEgx8yCa4SWimsRJLg6pKabg0AxlM6ZsivCW7uKuUg7N2zrOA2VhHJnPbs2eq79bya2RqOJAj6SbciW2maurzT6vcXCt9bwYNkrKNttr4-Ps4c7HbxEVULDwRXK3qedabmstEb16FQrXALzTSzxYM385fHa1RypctNLtcz8iQhLxR8mm_ZTIDPaUFBktjht-imMYsZ4vGg91Gm9kW693X-mFUvvJS1y28wDBQzqoWpryVGhVjDT9eAV3iT8f7TXmcHxmn4etov97hzn0NzTZihXG31LF_Nj-U0f4-jpbpLIyygtHD6x6vQzRqY-J-JRdocWAe0H2HNeelIrWA87rP9D2C-7E_PvLUCgpd_8Lcg4e081z7x89_NxFC_QxUZjvlA_RoTGHiWbHFNDcoWD2nhtMnbMjDKyT28H_mIzJ0UxL86t2DKf24MvT4Glj5PYnS-jsabKo85L-GuKhdG0TQ3scswSEWdqwMzVj4qPS94VxPvfliB-iT5oqK59Vk7SA7VcmpLurDHG46MnyVJGw5GS3qhORmc";

    public function testParseToken() {
        $jwtClass = new JWTController();
        $parseToken = $jwtClass->getToken(self::FULL_TOKEN_USER);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MTU5MzA0MTYsImV4cCI6MTYxNTkzNDAxNiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidXNlckB1c2VyLmNvbSJ9.hzUHwzqzXuff-4joae0PJKK8sXr7g2IwjQnA_XqzaYEpL2VrdlXlsgMr31yaSWDclZo4Mou3I_HM-c7EN4G3sNR01H8Ee3nD6uyKR08PAlOrX74JZLzl8ZuOJBrRokFJGYAIzhFp5Lxtk3C1lD9zI2WicuAlD7Kk1OAT3XPUR2tsPSMCYhQkcciPlvF6AKtPGJkvkTxNQc1NGFQN6YIzyCcFzNOP-HjALb-MC42f03pMJAcAay-jfvch0kE38ryENjDEG44VPPWIBGFFbA0cUqVOyZ-F6j_9tIPnAPBdVdWsCgm3gX7L6-C6TGNCF6cY-URqJCzaEByQMx6NG7eGsrrxWaX4O9_aB32n2Zlq-Y2TvgUwwHPSOatu_ItISU5zL73J3YNdQgY__2MaG8rrRyibd9apIUTacw0cCCzON3RwlOuVh2aHmwYiq16-rWdPSGeRykXpsyyq3fZNWhghIakEip1C0hGLpZxu9zaGWW7tYgrxgUaTFBcQnbl1m0PgxyozuODdfWNtQuLpA6_SWNTAFLdu02hagsQTcKRbndehEFL8v07z5MCunaIdqJUieAZ1Vl2VuD1sfA9krQ0GMwbuWlU1jUSJmru_M30CFjcVDu1qGrix7ChVkRD6u9Cwbe3ekl6eHdvI3h1Vh5ihbAQ4ZVUp08p5sTcZWYTe7Rw",$parseToken);
    }

    public function testTokenIsNotAdmin() {
        $jwtClass = new JWTController();
        $value = $jwtClass->checkIfAdmin(self::FULL_TOKEN_USER);
        $this->assertEquals(false, $value);
    }

    public function testTokenIsAdmin() {
        $jwtClass = new JWTController();
        $value = $jwtClass->checkIfAdmin(self::FULL_TOKEN_ADMIN);
        $this->assertEquals(true, $value);
    }

    public function testGetUsername() {
        $jwtClass = new JWTController();
        $mail = $jwtClass->getUsername(self::FULL_TOKEN_USER);
        $this->assertEquals("user@user.com", $mail);
    }

    public function testGetRole() {
        $jwtClass = new JWTController();
        $roles = $jwtClass->getRole(self::FULL_TOKEN_USER);
        $this->assertEquals(["ROLE_USER"], $roles);
    }

    public function testTokenIsValid() {
        $jwtClass = new JWTController();
        $isValid = $jwtClass->checkIfTokenValid(self::OLD_TOKEN);
        $this->assertEquals(false, $isValid);
    }

    public function testDecodeToken() {
        $jwtClass = new JWTController();
        $jwtParse = $jwtClass->decodeToken(self::FULL_TOKEN_USER);
        $this->assertEquals("user@user.com", $jwtParse->username);
    }

}